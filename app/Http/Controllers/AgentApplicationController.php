<?php

namespace App\Http\Controllers;

use App\Models\AgentApplication;
use App\Models\Department;
use App\Models\User;
use App\Notifications\NewAgentApplicationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AgentApplicationController extends Controller
{
    public function showForm()
    {
        return view('agent.apply');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|max:255|unique:agent_applications,email',
            'phone' => [
                'nullable',
                'string',
                'max:25',
                'philippine_phone',
                Rule::unique('agent_applications', 'phone'),
                Rule::unique('users', 'phone'),
            ],
            'cover_letter_file' => 'required|file|mimes:pdf,doc,docx,txt|max:5120',
            'certifications' => 'nullable|array',
            'certifications.*' => 'string|max:120',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $resume = $request->file('resume');
        $resumeFilename = sprintf(
            '%s_%s_resume_%s.%s',
            time(),
            Str::slug($validated['first_name'] . '_' . $validated['last_name']),
            Str::random(6),
            $resume->getClientOriginalExtension()
        );

        $resumePath = $resume->storeAs('resumes', $resumeFilename, 'public');

        $coverLetterFile = $request->file('cover_letter_file');
        $coverLetterFilename = sprintf(
            '%s_%s_cover_letter_%s.%s',
            time(),
            Str::slug($validated['first_name'] . '_' . $validated['last_name']),
            Str::random(6),
            $coverLetterFile->getClientOriginalExtension()
        );

        $coverLetterPath = $coverLetterFile->storeAs('cover_letters', $coverLetterFilename, 'public');

        $application = AgentApplication::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'cover_letter' => 'Uploaded cover letter file.',
            'cover_letter_path' => $coverLetterPath,
            'certifications' => $validated['certifications'] ?? [],
            'resume_path' => $resumePath,
            'status' => 'pending',
        ]);

        $this->sendApplicationConfirmation($application);
        $this->notifyAdminsOfApplication($application);

        return redirect()->route('application.success')->with('success', 'Your agent application was submitted successfully. We will review it and notify you by email and phone once approved.');
    }

    public function success()
    {
        return view('agent.application-success');
    }

    public function index(Request $request)
    {
        $query = AgentApplication::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && in_array($request->input('status'), ['pending', 'approved', 'rejected'], true)) {
            $query->where('status', $request->input('status'));
        }

        $applications = $query->orderByDesc('created_at')->paginate(12)->withQueryString();

        $totalApplications = AgentApplication::count();
        $pendingCount = AgentApplication::where('status', 'pending')->count();
        $approvedCount = AgentApplication::where('status', 'approved')->count();
        $rejectedCount = AgentApplication::where('status', 'rejected')->count();
        $orphanedCount = AgentApplication::where('status', 'approved')->doesntHave('user')->count();

        if ($request->ajax() || $request->wantsJson()) {
            $tableHtml = view('admin.applications.partials.table', compact('applications'))->render();
            return response()->json([
                'success' => true,
                'table_html' => $tableHtml,
                'results_count' => sprintf('Showing %s to %s of %s applications', $applications->firstItem() ?? 0, $applications->lastItem() ?? 0, $applications->total()),
                'stats' => [
                    'total' => $totalApplications,
                    'pending' => $pendingCount,
                    'approved' => $approvedCount,
                    'rejected' => $rejectedCount,
                ],
            ]);
        }

        return view('admin.applications.index', compact('applications', 'totalApplications', 'pendingCount', 'approvedCount', 'rejectedCount', 'orphanedCount'));
    }

    public function show(AgentApplication $application)
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        return view('admin.applications.show', compact('application', 'departments'));
    }

    public function approve(Request $request, AgentApplication $application)
    {
        if ($application->status !== 'pending') {
            return redirect()->route('admin.applications.show', $application)
                ->with('error', 'Only pending applications can be approved.');
        }

        $request->validate([
            'employee_id' => ['required','string','max:50','regex:/^AGT(-[A-Z0-9]{1,4}){1,2}$/'],
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:100',
            'specialization' => 'nullable|array',
            'specialization.*' => 'string|max:100',
            'skills' => 'nullable|string|max:255',
            'admin_notes' => 'nullable|string|max:2000',
        ], [
            'employee_id.required' => 'Employee ID must be generated and provided.',
            'employee_id.regex' => 'Employee ID must be valid and follow AGT-XXXX or AGT-DEPT-XXXX format.',
        ]);

        // Use the employee_id from the form (generated in modal)
        $employeeId = $request->input('employee_id');
        $department = Department::find($request->input('department_id'));
        
        // ALWAYS generate a temporary password for agents being approved
        $generatedPassword = Str::random(10);

        $agentData = [
            'name' => $application->full_name,
            'phone' => $application->phone,
            'role' => 'agent',
            'status' => 'active',
            'agent_application_id' => $application->id,
            'employee_id' => $employeeId,
            'department_id' => $department?->id,
            'position' => $request->input('position'),
            'specialization' => $this->normalizeSpecializationInput($request->input('specialization')),
            'skills' => $request->input('skills'),
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'password' => Hash::make($generatedPassword), // Always set the new temporary password
        ];

        $pendingAgent = User::where('email', $application->email)->first();

        if ($pendingAgent) {
            // Update existing user with agent data (including the new temporary password)
            $pendingAgent->update($agentData);
        } else {
            $agentData['email'] = $application->email;
            $pendingAgent = User::create($agentData);
        }

        // FORCE email verification using forceFill to ensure it saves properly
        $pendingAgent->forceFill([
            'email_verified_at' => now(),
        ])->save();

        if (method_exists($pendingAgent, 'syncRoles')) {
            try {
                $pendingAgent->syncRoles('agent');
            } catch (\Throwable $e) {
                // Ignore role sync failures when permissions are not configured.
            }
        }

        // Store the generated employee_id and password in the application record for future reference
        $application->update([
            'status' => 'approved',
            'admin_notes' => $request->input('admin_notes'),
            'reviewed_by' => Auth::id(),
            'generated_employee_id' => $employeeId,
            'generated_password' => $generatedPassword, // Store plain password for admin records only
        ]);

        $this->sendAgentApproval($application, $pendingAgent, $generatedPassword);

        return redirect()->route('admin.applications.show', $application)->with('success', 'Application approved, agent account activated, and notification sent to email' . ($application->phone ? ' and phone.' : '.'));
    }

    protected function normalizeSpecializationInput($specialization): ?string
    {
        if (is_array($specialization)) {
            $specializations = array_filter(array_map('trim', $specialization));
            return $specializations ? implode(', ', $specializations) : null;
        }

        if (is_string($specialization) && trim($specialization) !== '') {
            return trim($specialization);
        }

        return null;
    }

    protected function generateEmployeeId(?string $department = null): string
    {
        $prefix = 'AGT';
        $departmentCode = '';

        if ($department) {
            $departmentCode = preg_replace('/[^A-Z0-9]/', '', strtoupper($department));
            $departmentCode = substr($departmentCode, 0, 4);
        }

        if (! $departmentCode) {
            $departmentCode = 'GEN';
        }

        do {
            $random = strtoupper(Str::random(4));
            $employeeId = $departmentCode === 'GEN'
                ? "{$prefix}-{$random}"
                : "{$prefix}-{$departmentCode}-{$random}";
        } while (User::where('employee_id', $employeeId)->exists());

        return $employeeId;
    }

    public function reject(Request $request, AgentApplication $application)
    {
        if ($application->status !== 'pending') {
            return redirect()->route('admin.applications.show', $application)
                ->with('error', 'Only pending applications can be rejected.');
        }

        $request->validate([
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $application->update([
            'status' => 'rejected',
            'admin_notes' => $request->input('admin_notes'),
            'reviewed_by' => Auth::id(),
        ]);

        $this->sendApplicationRejected($application);

        return redirect()->route('admin.applications.show', $application)->with('success', 'The application has been rejected and notification sent to email' . ($application->phone ? ' and phone.' : '.'));
    }

    public function destroy(AgentApplication $application)
    {
        if ($application->status === 'approved') {
            return redirect()->route('admin.applications')->with('error', 'Approved applications cannot be deleted. Please reject the application first.');
        }

        if ($application->resume_path) {
            Storage::disk('public')->delete($application->resume_path);
        }

        if ($application->cover_letter_path) {
            Storage::disk('public')->delete($application->cover_letter_path);
        }

        $application->delete();

        return redirect()->route('admin.applications')->with('success', 'Application deleted successfully.');
    }

    public function cleanupOrphaned(Request $request)
    {
        $orphanedApplications = AgentApplication::where('status', 'approved')
            ->doesntHave('user')
            ->get();

        foreach ($orphanedApplications as $application) {
            if ($application->resume_path && Storage::exists($application->resume_path)) {
                Storage::delete($application->resume_path);
            }
            if ($application->cover_letter_path && Storage::exists($application->cover_letter_path)) {
                Storage::delete($application->cover_letter_path);
            }
            $application->delete();
        }

        return redirect()->route('admin.applications')
            ->with('success', $orphanedApplications->count() . ' orphaned application(s) have been removed.');
    }

    public function bulkDestroy(Request $request)
    {
        $applicationIds = $request->application_ids;

        if (is_string($applicationIds)) {
            $applicationIds = explode(',', $applicationIds);
        }

        $applicationIds = array_filter($applicationIds, function ($id) {
            return is_numeric($id);
        });

        if (empty($applicationIds)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'No applications selected for deletion.'], 422);
            }

            return redirect()->route('admin.applications')->with('error', 'No applications selected for deletion.');
        }

        $applications = AgentApplication::whereIn('id', $applicationIds)->get();
        $approvedApplications = $applications->where('status', 'approved');

        if ($approvedApplications->isNotEmpty()) {
            $ids = $approvedApplications->pluck('id')->implode(', ');
            $message = 'Approved applications cannot be deleted. Please reject them first. Approved IDs: ' . $ids;

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }

            return redirect()->route('admin.applications')->with('error', $message);
        }

        foreach ($applications as $application) {
            if ($application->resume_path) {
                Storage::disk('public')->delete($application->resume_path);
            }

            if ($application->cover_letter_path) {
                Storage::disk('public')->delete($application->cover_letter_path);
            }

            $application->delete();
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => count($applications) . ' applications deleted successfully.']);
        }

        return redirect()->route('admin.applications')->with('success', count($applications) . ' applications deleted successfully.');
    }

    protected function sendApplicationConfirmation(AgentApplication $application): void
    {
        $this->sendNotificationEmail(
            'emails.agent_applications.application-confirmation',
            compact('application'),
            $application->email,
            'Agent Application Received'
        );
    }

    protected function notifyAdminsOfApplication(AgentApplication $application): void
    {
        $admins = User::where('role', 'admin')->get();

        if ($admins->isEmpty()) {
            return;
        }

        Notification::sendNow($admins, new NewAgentApplicationNotification($application));
    }

    protected function sendAgentApproval(AgentApplication $application, User $user, ?string $password = null): void
    {
        $this->sendNotificationEmail(
            'emails.agent_applications.agent-approved',
            compact('application', 'user', 'password'),
            $user->email,
            'Congratulations! Your Agent Application Has Been Approved'
        );

        if ($user->phone) {
            $smsMessage = "Hello {$user->name}, your agent application has been approved. Your Agent ID is {$user->employee_id}.";

            if ($password) {
                $smsMessage .= " Your temporary password is {$password}.";
            } else {
                $smsMessage .= " Use the password you created during registration.";
            }

            $smsMessage .= " Use your registered email and password to sign in after approval.";

            $this->sendSmsNotification($user->phone, $smsMessage);
        }
    }

    protected function sendApplicationRejected(AgentApplication $application): void
    {
        $this->sendNotificationEmail(
            'emails.agent_applications.application-rejected',
            compact('application'),
            $application->email,
            'Your Agent Application Status'
        );

        if ($application->phone) {
            $smsMessage = "Hello {$application->first_name}, your agent application was not approved at this time.";

            if ($application->admin_notes) {
                $smsMessage .= " Note: {$application->admin_notes}";
            }

            $this->sendSmsNotification($application->phone, $smsMessage);
        }
    }

    protected function sendNotificationEmail(string $view, array $data, string|array $recipient, string $subject): bool
    {
        try {
            Mail::send($view, $data, function ($message) use ($recipient, $subject) {
                if (is_array($recipient)) {
                    $message->to($recipient);
                } else {
                    $message->to($recipient);
                }

                $message->subject($subject);

                if (config('mail.from.address')) {
                    $message->from(config('mail.from.address'), config('mail.from.name'));
                }
            });

            return true;
        } catch (\Throwable $e) {
            Log::error('Agent application email notification failed', [
                'recipient' => $recipient,
                'subject' => $subject,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    protected function sendSmsNotification(?string $phone, string $message): bool
    {
        if (! $phone) {
            return false;
        }

        $apiUrl = config('services.sms.api_url');
        $apiKey = config('services.sms.api_key');
        $from = config('services.sms.from');

        if (! $apiUrl || ! $apiKey) {
            return false;
        }

        try {
            $payload = [
                'apikey' => $apiKey,
                'number' => $phone,
                'message' => $message,
            ];

            if ($from) {
                $payload['sendername'] = $from;
            }

            $response = Http::acceptJson()
                ->post($apiUrl, $payload);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::warning('SMS notification failed', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function downloadFile(AgentApplication $application, string $fileType)
    {
        $filePath = null;
        $filename = null;

        if ($fileType === 'resume' && $application->resume_path) {
            $filePath = $application->resume_path;
            $filename = 'resume_' . $application->full_name . '.' . pathinfo($application->resume_path, PATHINFO_EXTENSION);
        } elseif ($fileType === 'cover_letter' && $application->cover_letter_path) {
            $filePath = $application->cover_letter_path;
            $filename = 'cover_letter_' . $application->full_name . '.' . pathinfo($application->cover_letter_path, PATHINFO_EXTENSION);
        }

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found');
        }

        try {
            return Storage::disk('public')->download($filePath, $filename, [
                'Content-Type' => $this->getMimeType($filePath),
            ]);
        } catch (\Exception $e) {
            Log::error('File download failed', ['path' => $filePath, 'error' => $e->getMessage()]);
            abort(500, 'Error downloading file');
        }
    }

    public function viewFile(AgentApplication $application, string $fileType)
    {
        $filePath = null;

        if ($fileType === 'resume' && $application->resume_path) {
            $filePath = $application->resume_path;
        } elseif ($fileType === 'cover_letter' && $application->cover_letter_path) {
            $filePath = $application->cover_letter_path;
        }

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found');
        }

        $mimeType = $this->getMimeType($filePath);
        $fileExt = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $publicUrl = Storage::disk('public')->url($filePath);
        if (!str_contains($publicUrl, '://')) {
            $publicUrl = url($publicUrl);
        }

        if ($fileExt === 'pdf') {
            try {
                return Storage::disk('public')->response($filePath, null, [
                    'Content-Type' => $mimeType,
                    'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"',
                ]);
            } catch (\Exception $e) {
                Log::error('File view failed', ['path' => $filePath, 'error' => $e->getMessage()]);
                abort(500, 'Error viewing file');
            }
        }

        $viewerUrl = null;
        if (in_array($fileExt, ['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'])) {
            $viewerUrl = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode($publicUrl);
        }

        $fileContent = null;
        if ($fileExt === 'txt') {
            $fileContent = Storage::disk('public')->get($filePath);
        }

        return view('admin.applications.file-viewer', [
            'application' => $application,
            'fileType' => $fileType,
            'filePath' => $filePath,
            'fileName' => basename($filePath),
            'fileUrl' => $publicUrl,
            'fileExt' => $fileExt,
            'fileContent' => $fileContent,
            'viewerUrl' => $viewerUrl,
        ]);
    }

    protected function getMimeType(string $filePath): string
    {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        return match ($ext) {
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'txt' => 'text/plain',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            default => 'application/octet-stream',
        };
    }
}
