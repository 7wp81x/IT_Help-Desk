<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentApplication;
use App\Models\User;
use App\Models\Department;
use App\Models\Category;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display all users
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }
        
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('department') && $request->department !== 'all') {
            $departmentValue = $request->department;
            if (is_numeric($departmentValue)) {
                $query->where('department_id', $departmentValue);
            } else {
                $query->whereHas('department', function ($query) use ($departmentValue) {
                    $query->where('name', $departmentValue);
                });
            }
        }
        
        $users = $query->with(['agentApplication', 'department'])->latest()->paginate(10)->withQueryString();
        
        $departments = Department::whereHas('users')->orderBy('name')->get();
        
        $stats = [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'agents' => User::where('role', 'agent')->count(),
            'users' => User::where('role', 'user')->count(),
        ];
        
        if ($request->ajax() || $request->has('ajax')) {
            $html = view('admin.users.partials.table', compact('users'))->render();
            $resultsCount = "Showing " . $users->firstItem() . " to " . $users->lastItem() . " of " . $users->total() . " results";
            
            return response()->json([
                'html' => $html,
                'results_count' => $resultsCount,
                'stats' => $stats
            ]);
        }
        
        return view('admin.users.tabs.all', compact('users', 'stats', 'departments'));
    }

    /**
     * Show form for creating new user.
     */
    public function create(Request $request)
{
    $departments = Department::where('is_active', true)->orderBy('name')->get();
    $categories = Category::where('is_active', true)->orderBy('name')->get(); // ADD THIS LINE
    
    $role = $request->get('role');

    if ($role === 'agent') {
        return view('admin.users.create_agent', compact('departments', 'categories')); // ADD 'categories'
    }

    if ($role === 'admin') {
        return view('admin.users.create_admin', compact('departments'));
    }

    return view('admin.users.create_user', compact('departments', 'categories'));
}

    /**
     * Store a newly created user.
     */
public function store(Request $request)
{
    $baseRules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8|confirmed',
        'role' => 'required|in:admin,agent,user',
        'phone' => [
            'nullable',
            'string',
            'max:20',
            'philippine_phone',
            Rule::unique('users', 'phone'),
        ],
        'department_id' => 'nullable|exists:departments,id',
        'department' => 'nullable|string|max:255',
        'position' => 'nullable|string|max:255',
        'specialization' => 'nullable|string',
        'skills' => 'nullable|string',
        'status' => 'sometimes|in:active,inactive',
        'category_ids' => 'nullable|array',
        'category_ids.*' => 'exists:categories,id',
    ];

    $role = $request->get('role');

    // employee_id is required for admin and agent, forbidden for end users
    if ($role === 'admin' || $role === 'agent') {
        $baseRules['employee_id'] = 'required|string|unique:users,employee_id';
    }

    $validated = $request->validate($baseRules);

    if (empty($validated['department_id']) && $request->filled('department')) {
        $department = Department::where('name', $request->department)->first();
        $validated['department_id'] = $department ? $department->id : null;
    }

    unset($validated['department']);

    $validated['password'] = Hash::make($validated['password']);
    $validated['status'] = $validated['status'] ?? 'active';

    // ensure end users don't get an employee_id
    if ($validated['role'] === 'user') {
        unset($validated['employee_id']);
    }

    $user = User::create($validated);

    // Sync categories for agents
    if ($role === 'agent' && $request->has('category_ids')) {
        $user->categories()->sync($request->category_ids);
    }

    return redirect()->route('admin.users.index')
        ->with('success', 'User created successfully.');
}  // <-- MAKE SURE THIS CLOSING BRACE EXISTS
        
            /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $stats = [
            'total_tickets' => $user->tickets()->count(),
            'open_tickets' => $user->tickets()->whereIn('status', ['open', 'in_progress'])->count(),
            'resolved_tickets' => $user->tickets()->where('status', 'resolved')->count(),
            'closed_tickets' => $user->tickets()->where('status', 'closed')->count(),
        ];

        $recentTickets = $user->tickets()->latest()->take(5)->get();

        return view('admin.users.show', compact('user', 'stats', 'recentTickets'));
    }

   
    /**
 * Show form for editing user.
 */
public function edit(User $user)
{
    $departments = Department::where('is_active', true)->orderBy('name')->get();
    $categories = Category::where('is_active', true)->orderBy('name')->get(); // ADD THIS
    
    $user->load('categories'); // ADD THIS

    if ($user->role === 'agent') {
        return view('admin.users.edit_agent', compact('user', 'departments', 'categories')); // ADD 'categories'
    }

    if ($user->role === 'admin') {
        return view('admin.users.edit_admin', compact('user', 'departments'));
    }

    return view('admin.users.edit_user', compact('user', 'departments'));
}

    /**
     * Update the specified user.
     */
   public function update(Request $request, User $user)
{
    $baseRules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role' => 'required|in:admin,agent,user',
        'phone' => [
            'nullable',
            'string',
            'max:20',
            'philippine_phone',
            Rule::unique('users', 'phone')->ignore($user->id),
        ],
        'department_id' => 'nullable|exists:departments,id',
        'department' => 'nullable|string|max:255',
        'position' => 'nullable|string|max:255',
        'specialization' => 'nullable|string',
        'skills' => 'nullable|string',
        'status' => 'required|in:active,inactive',
        'category_ids' => 'nullable|array',           // ADD THIS
        'category_ids.*' => 'exists:categories,id',   // ADD THIS
        'day_off' => 'sometimes|boolean',
        'shift_start' => 'nullable|date_format:H:i',
        'shift_end' => 'nullable|date_format:H:i',
    ];

    $role = $request->get('role');

    if ($role === 'admin' || $role === 'agent') {
        $baseRules['employee_id'] = 'required|string|unique:users,employee_id,' . $user->id;
    }

    $validated = $request->validate($baseRules);

    if (empty($validated['department_id']) && $request->filled('department')) {
        $department = Department::where('name', $request->department)->first();
        $validated['department_id'] = $department ? $department->id : null;
    }

    unset($validated['department']);

    if ($request->filled('password')) {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);
        $validated['password'] = Hash::make($request->password);
    }

    if ($validated['role'] === 'agent') {
        $validated['day_off'] = $request->has('day_off');
        if ($request->filled('shift_start') || $request->filled('shift_end')) {
            $validated['schedule'] = [
                'shift_start' => $request->input('shift_start'),
                'shift_end' => $request->input('shift_end'),
            ];
        } else {
            $validated['schedule'] = null;
        }
    } else {
        $validated['day_off'] = false;
        $validated['schedule'] = null;
    }

    // If role is end user, ensure employee_id is removed
    if ($validated['role'] === 'user') {
        $validated['employee_id'] = null;
    }

    $user->update($validated);

    // Sync categories for agents
    if ($validated['role'] === 'agent') {
        if ($request->has('category_ids')) {
            $user->categories()->sync($request->category_ids);
        } else {
            $user->categories()->sync([]);
        }
    } else {
        $user->categories()->sync([]);
    }

    return redirect()->route('admin.users.index')
        ->with('success', 'User updated successfully.');
}
    /**
     * Remove the specified user.
     */
  /**
 * Remove the specified user.
 */
public function destroy(User $user)
{
    // Check if it's an AJAX request
    $isAjax = request()->ajax() || request()->wantsJson();
    
    if ($user->id === Auth::id()) {
        if ($isAjax) {
            return response()->json(['success' => false, 'message' => 'You cannot delete your own account.']);
        }
        return back()->with('error', 'You cannot delete your own account.');
    }

    try {
        if ($user->role === 'agent') {
            $this->notifyAgentAccountDeleted($user);
            $this->revertLinkedAgentApplication($user);
        }

        if ($user->avatar && Storage::exists('avatars/' . $user->avatar)) {
            Storage::delete('avatars/' . $user->avatar);
        }

        $user->delete();

        if ($isAjax) {
            return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
            
    } catch (\Exception $e) {
        Log::error('Failed to delete user', [
            'user_id' => $user->id,
            'error' => $e->getMessage(),
        ]);
        
        if ($isAjax) {
            return response()->json(['success' => false, 'message' => 'An error occurred while deleting the user.']);
        }
        
        return back()->with('error', 'An error occurred while deleting the user.');
    }
}

    /**
     * Remove the specified user via AJAX.
     */
    public function destroyAjax(User $user)
    {
        if ($user->id === Auth::id()) {
            return response()->json(['success' => false, 'message' => 'You cannot delete your own account.']);
        }

        try {
            if ($user->role === 'agent') {
                $this->notifyAgentAccountDeleted($user);
                $this->revertLinkedAgentApplication($user);
            } elseif ($user->role === 'user') {
                $this->notifyEndUserAccountDeleted($user);
            }

            if ($user->avatar && Storage::exists('avatars/' . $user->avatar)) {
                Storage::delete('avatars/' . $user->avatar);
            }

            $user->delete();

            return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
        } catch (\Exception $e) {
            \Log::error('Failed to delete user', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['success' => false, 'message' => 'An error occurred while deleting the user.']);
        }
    }

    /**
     * Bulk delete users.
     */
    public function bulkDestroy(Request $request)
    {
        $userIds = $request->user_ids;
        
        // Handle both array and comma-separated string formats
        if (is_string($userIds)) {
            $userIds = explode(',', $userIds);
        }
        
        $userIds = array_filter($userIds, function($id) {
            return $id != Auth::id();
        });
        
        if (empty($userIds)) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Cannot delete your own account.']);
            }
            return redirect()->route('admin.users.index')->with('error', 'Cannot delete your own account.');
        }
        
        try {
            // Handle foreign key constraints by setting related records to null
            // Comments and ticket activities should be preserved for audit purposes
            DB::table('comments')->whereIn('user_id', $userIds)->update(['user_id' => null]);
            DB::table('ticket_activities')->whereIn('user_id', $userIds)->update(['user_id' => null]);
            
            $agentUsers = User::whereIn('id', $userIds)
                ->where('role', 'agent')
                ->get(['id', 'name', 'email', 'phone', 'agent_application_id']);

            $agentApplicationIds = $agentUsers->pluck('agent_application_id')
                ->filter()
                ->unique()
                ->toArray();

            $agentEmailsWithoutApplicationId = $agentUsers->whereNull('agent_application_id')
                ->pluck('email')
                ->filter()
                ->unique()
                ->toArray();

            if (! empty($agentEmailsWithoutApplicationId)) {
                $fallbackApplicationIds = AgentApplication::whereIn('email', $agentEmailsWithoutApplicationId)
                    ->where('status', 'approved')
                    ->pluck('id')
                    ->filter()
                    ->unique()
                    ->toArray();

                $agentApplicationIds = array_merge($agentApplicationIds, $fallbackApplicationIds);
            }

            if (! empty($agentApplicationIds)) {
                AgentApplication::whereIn('id', $agentApplicationIds)
                    ->where('status', 'approved')
                    ->update([
                        'status' => 'rejected',
                        'admin_notes' => 'Linked agent account deleted. Application marked rejected.',
                        'reviewed_by' => Auth::id(),
                    ]);
            }

            $agentUsersToNotify = $agentUsers->filter(fn ($agent) => $agent->email)->values();
            foreach ($agentUsersToNotify as $agentUser) {
                $this->notifyAgentAccountDeleted($agentUser);
            }

            // Get and notify end users
            $endUsers = User::whereIn('id', $userIds)
                ->where('role', 'user')
                ->get(['id', 'name', 'email', 'phone']);

            $endUsersToNotify = $endUsers->filter(fn ($user) => $user->email)->values();
            foreach ($endUsersToNotify as $endUser) {
                $this->notifyEndUserAccountDeleted($endUser);
            }

            // Delete the users (this will cascade delete other related records)
            $deletedCount = User::whereIn('id', $userIds)->delete();
            
            Log::info("Bulk delete completed: {$deletedCount} users deleted", ['user_ids' => $userIds]);
            
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => count($userIds) . ' users deleted successfully.']);
            }
            
            return redirect()->route('admin.users.index')->with('success', count($userIds) . ' users deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Bulk user delete error: ' . $e->getMessage(), [
                'user_ids' => $userIds,
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'An error occurred while deleting users.'], 500);
            }
            
            return redirect()->route('admin.users.index')->with('error', 'An error occurred while deleting users.');
        }
    }

    protected function revertLinkedAgentApplication(User $user): void
    {
        try {
            $query = AgentApplication::where('status', 'approved');

            if ($user->agent_application_id) {
                $query->where('id', $user->agent_application_id);
            } else {
                $query->where('email', $user->email);
            }

            $query->update([
                'status' => 'rejected',
                'admin_notes' => 'Linked agent account deleted. Application marked rejected.',
                'reviewed_by' => Auth::id(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to revert agent application', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function notifyAgentAccountDeleted(User $user): void
    {
        try {
            if ($user->email) {
                Mail::send('emails.agent_applications.agent-deleted', compact('user'), function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Your Agent Account Has Been Deleted');
                });
            }

            if ($user->phone) {
                $smsMessage = "Hello {$user->name}, your agent account has been deleted by the administrator. "
                    . "If you believe this was a mistake, please contact support.";
                $this->sendSmsNotification($user->phone, $smsMessage);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send agent deletion notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function notifyEndUserAccountDeleted(User $user): void
    {
        try {
            if ($user->email) {
                Mail::send('emails.user_deleted', compact('user'), function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Your Account Has Been Deleted');
                });
            }

            if ($user->phone) {
                $smsMessage = "Hello {$user->name}, your account has been deleted by the administrator. "
                    . "If you believe this was a mistake, please contact support.";
                $this->sendSmsNotification($user->phone, $smsMessage);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send end user deletion notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
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
            Log::warning('SMS notification failed for account deletion', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
    
    /**
     * Toggle user status.
     */
    public function toggleStatus(Request $request, User $user)
    {
        if ($user->id === Auth::id()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'You cannot change the status of your own account.']);
            }

            return back()->with('error', 'You cannot change the status of your own account.');
        }

        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'User status updated successfully.']);
        }

        return back()->with('success', 'User status updated successfully.');
    }

    /**
     * Display pending agent registrations
     */
    public function pendingAgents(Request $request)
    {
        $query = User::where('role', 'pending_agent');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $pendingAgents = $query->latest()->paginate(15)->withQueryString();
        $totalPending = User::where('role', 'pending_agent')->count();
        
        return view('admin.users.pending-agents', compact('pendingAgents', 'totalPending'));
    }

    /**
     * Approve a pending agent registration
     */
    public function approvePendingAgent(Request $request, User $user)
    {
        if ($user->role !== 'pending_agent') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'This user is not a pending agent.'], 422);
            }
            return back()->with('error', 'This user is not a pending agent.');
        }

        $validated = $request->validate([
            'department' => 'required|string|max:100',
            'position' => 'required|string|max:100',
            'specialization' => 'nullable|string|max:255',
            'skills' => 'nullable|string|max:500',
        ]);

        // Generate employee ID if not set
        if (!$user->employee_id) {
            $user->employee_id = $this->generateEmployeeId($validated['department']);
        }

        $specializations = $this->normalizeSpecializationInput($validated['specialization']);

        // Update user to agent
        $user->update([
            'role' => 'agent',
            'status' => 'active',
            'department' => $validated['department'],
            'position' => $validated['position'],
            'specialization' => $specializations,
            'skills' => $validated['skills'] ?? null,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        // Sync roles if available
        if (method_exists($user, 'syncRoles')) {
            try {
                $user->syncRoles('agent');
            } catch (\Throwable $e) {
                // Ignore role sync failures
            }
        }

        // Send approval email
        try {
            Mail::send('emails.agent-approved', [
                'user' => $user,
                'employeeId' => $user->employee_id,
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Your Agent Account Has Been Approved!');
            });
        } catch (\Throwable $e) {
            Log::error('Failed to send agent approval email: ' . $e->getMessage());
        }

        $message = 'Agent ' . $user->name . ' has been approved and can now access the agent dashboard.';
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->route('admin.users.pending-agents')->with('success', $message);
    }

    /**
     * Reject a pending agent registration
     */
    public function rejectPendingAgent(Request $request, User $user)
    {
        if ($user->role !== 'pending_agent') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'This user is not a pending agent.'], 422);
            }
            return back()->with('error', 'This user is not a pending agent.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        // Delete the pending agent user
        $email = $user->email;
        $name = $user->name;
        $user->delete();

        // Send rejection email
        try {
            Mail::send('emails.agent-rejected', [
                'name' => $name,
                'email' => $email,
                'reason' => $validated['rejection_reason'],
            ], function ($message) use ($email) {
                $message->to($email)
                        ->subject('Agent Registration Status');
            });
        } catch (\Throwable $e) {
            Log::error('Failed to send agent rejection email: ' . $e->getMessage());
        }

        $message = 'Agent application has been rejected and user notified.';
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->route('admin.users.pending-agents')->with('success', $message);
    }

    /**
     * Generate a unique employee ID
     */
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

        if (!$departmentCode) {
            $departmentCode = 'GEN';
        }

        do {
            $random = strtoupper(\Illuminate\Support\Str::random(4));
            $employeeId = $departmentCode === 'GEN'
                ? "{$prefix}-{$random}"
                : "{$prefix}-{$departmentCode}-{$random}";
        } while (User::where('employee_id', $employeeId)->exists());

        return $employeeId;
    }
}