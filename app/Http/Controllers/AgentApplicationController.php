<?php

namespace App\Http\Controllers;

use App\Models\AgentApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            'phone' => 'nullable|string|max:25',
            'cover_letter' => 'required|string|max:2000',
            'certifications' => 'nullable|array',
            'certifications.*' => 'string|max:120',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $resume = $request->file('resume');
        $filename = sprintf(
            '%s_%s_%s.%s',
            time(),
            Str::slug($validated['first_name'] . '_' . $validated['last_name']),
            Str::random(6),
            $resume->getClientOriginalExtension()
        );

        $path = $resume->storeAs('resumes', $filename, 'public');

        $application = AgentApplication::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'cover_letter' => $validated['cover_letter'],
            'certifications' => $validated['certifications'] ?? [],
            'resume_path' => $path,
            'status' => 'pending',
        ]);

        $this->sendApplicationConfirmation($application);
        $this->notifyAdminsOfApplication($application);

        return redirect()->route('application.success');
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

        return view('admin.applications.index', compact('applications'));
    }

    public function show(AgentApplication $application)
    {
        return view('admin.applications.show', compact('application'));
    }

    public function approve(Request $request, AgentApplication $application)
    {
        $request->validate([
            'employee_id' => ['nullable','string','max:50','regex:/^AGT(-[A-Z0-9]{1,4}){1,2}$/'],
            'department' => 'required|string|max:100',
            'position' => 'required|string|max:100',
            'specialization' => 'nullable|string|max:100',
            'skills' => 'nullable|string|max:255',
            'admin_notes' => 'nullable|string|max:2000',
        ], [
            'employee_id.regex' => 'Employee ID must be valid and follow AGT-XXXX or AGT-DEPT-XXXX format.',
        ]);

        $employeeId = $request->input('employee_id') ?: $this->generateEmployeeId($request->input('department'));
        $generatedPassword = null;

        $pendingAgent = User::where('email', $application->email)->first();

        if ($pendingAgent) {
            $pendingAgent->name = $application->full_name;
            $pendingAgent->phone = $application->phone;
            $pendingAgent->role = 'agent';
            $pendingAgent->status = 'active';
            $pendingAgent->agent_application_id = $application->id;
            $pendingAgent->employee_id = $employeeId;
            $pendingAgent->department = $request->input('department');
            $pendingAgent->position = $request->input('position');
            $pendingAgent->specialization = $request->input('specialization');
            $pendingAgent->skills = $request->input('skills');
            $pendingAgent->approved_at = now();
            $pendingAgent->approved_by = Auth::id();
            $pendingAgent->save();
        } else {
            $generatedPassword = Str::random(10);

            $pendingAgent = User::create([
                'name' => $application->full_name,
                'email' => $application->email,
                'phone' => $application->phone,
                'password' => Hash::make($generatedPassword),
                'role' => 'agent',
                'status' => 'active',
                'agent_application_id' => $application->id,
                'employee_id' => $employeeId,
                'department' => $request->input('department'),
                'position' => $request->input('position'),
                'specialization' => $request->input('specialization'),
                'skills' => $request->input('skills'),
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ]);
        }

        if (method_exists($pendingAgent, 'syncRoles')) {
            try {
                $pendingAgent->syncRoles('agent');
            } catch (\Throwable $e) {
                // Ignore role sync failures when permissions are not configured.
            }
        }

        $application->update([
            'status' => 'approved',
            'admin_notes' => $request->input('admin_notes'),
            'reviewed_by' => Auth::id(),
        ]);

        $this->sendAgentApproval($application, $pendingAgent, $generatedPassword);

        return redirect()->route('admin.applications.show', $application)->with('success', 'Application approved, agent account activated, and notification sent to email' . ($application->phone ? ' and phone.' : '.'));
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
            return redirect()->route('admin.applications')->with('error', 'Approved applications cannot be deleted.');
        }

        if ($application->resume_path) {
            Storage::disk('public')->delete($application->resume_path);
        }

        $application->delete();

        return redirect()->route('admin.applications')->with('success', 'Application deleted successfully.');
    }

    protected function sendApplicationConfirmation(AgentApplication $application): void
    {
        $recipients = [$application->email];

        Mail::send('emails.agent_applications.application-confirmation', compact('application'), function ($message) use ($application) {
            $message->to($application->email)
                ->subject('Agent Application Received');
        });
    }

    protected function notifyAdminsOfApplication(AgentApplication $application): void
    {
        $adminEmails = User::where('role', 'admin')->pluck('email')->filter()->toArray();

        if (empty($adminEmails)) {
            return;
        }

        Mail::send('emails.agent_applications.admin-notification', compact('application'), function ($message) use ($adminEmails) {
            $message->to($adminEmails)
                ->subject('New Agent Application Submitted');
        });
    }

    protected function sendAgentApproval(AgentApplication $application, User $user, ?string $password = null): void
    {
        Mail::send('emails.agent_applications.agent-approved', compact('application', 'user', 'password'), function ($message) use ($user) {
            $message->to($user->email)
                ->subject('🎉 Congratulations! Your Agent Application Has Been Approved');
        });

        if ($user->phone) {
            $smsMessage = "Hello {$user->name}, your agent application has been approved. Your Agent ID is {$user->employee_id}.";

            if ($password) {
                $smsMessage .= " Your temporary password is {$password}.";
            }

            $smsMessage .= " Log in at " . url('/login') . ".";

            $this->sendSmsNotification($user->phone, $smsMessage);
        }
    }

    protected function sendApplicationRejected(AgentApplication $application): void
    {
        Mail::send('emails.agent_applications.application-rejected', compact('application'), function ($message) use ($application) {
            $message->to($application->email)
                ->subject('Your Agent Application Status');
        });

        if ($application->phone) {
            $smsMessage = "Hello {$application->first_name}, your agent application was not approved at this time.";

            if ($application->admin_notes) {
                $smsMessage .= " Note: {$application->admin_notes}";
            }

            $this->sendSmsNotification($application->phone, $smsMessage);
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
                'to' => $phone,
                'message' => $message,
            ];

            if ($from) {
                $payload['from'] = $from;
            }

            $response = Http::withToken($apiKey)
                ->acceptJson()
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
}
