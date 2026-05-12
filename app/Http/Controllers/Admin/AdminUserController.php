<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\TicketActivity;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    /**
     * Display admins only
     */

   public function index(Request $request)
{
    $query = User::where('role', 'admin');
    
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('employee_id', 'like', "%{$search}%");
        });
    }
    
    if ($request->filled('status') && $request->status !== 'all') {
        $query->where('status', $request->status);
    }
    
    $admins = $query->latest()->paginate(10);
    
    $stats = [
        'admins' => User::where('role', 'admin')->count(),
        'active_admins' => User::where('role', 'admin')->where('status', 'active')->count(),
        'total_permissions' => Permission::count(),
    ];

    // For AJAX requests - return ONLY the table HTML
    if ($request->ajax()) {
        $tableHtml = view('admin.users.tabs.admins', compact('admins', 'stats'))->render();
        return response()->json([
            'table_html' => $tableHtml,
            'results_count' => "Showing " . ($admins->firstItem() ?? 0) . " to " . ($admins->lastItem() ?? 0) . " of " . $admins->total() . " admins",
            'stats' => $stats
        ]);
    }

    return view('admin.users.tabs.admins', compact('admins', 'stats'));
}

    /**
     * Show permission manager for administrators.
     */
    public function permissions(Request $request, User $selectedAdmin = null)
    {
        $admins = User::where('role', 'admin')->orderBy('name')->get();

        if ($selectedAdmin && $selectedAdmin->role !== 'admin') {
            abort(404);
        }

        $selectedAdminPermissions = $selectedAdmin ? $selectedAdmin->getPermissionNames()->toArray() : [];

        return view('admin.users.admin-features.permissions', compact('admins', 'selectedAdmin', 'selectedAdminPermissions'));
    }

    /**
     * Update administrator permissions.
     */
    public function updatePermissions(Request $request, User $selectedAdmin)
    {
        if ($selectedAdmin->role !== 'admin') {
            abort(404);
        }

        $permissions = $request->input('permissions', []);
        $selectedAdmin->syncPermissions($permissions);

        return redirect()->route('admin.users.admins.permissions', $selectedAdmin)
            ->with('success', 'Permissions updated successfully.');
    }

    /**
     * Display the audit logs.
     */
    public function auditLogs(Request $request)
    {
        $query = TicketActivity::with('user')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('action') && $request->action !== 'all') {
            $query->where('action', $request->action);
        }

        if ($request->filled('user_id') && $request->user_id !== 'all') {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->paginate(15)->withQueryString();
        $users = User::orderBy('name')->get();

        return view('admin.users.admin-features.audit-logs', compact('logs', 'users'));
    }

    /**
     * Display system settings page.
     */
    public function systemSettings()
    {
        $settings = [
            'system_name' => config('app.name'),
            'system_email' => config('mail.from.address', 'support@helpdesk.com'),
            'timezone' => config('app.timezone', 'UTC'),
            'maintenance_mode' => config('app.maintenance', false),
            'auto_close_days' => 7,
            'max_tickets_per_user' => 0,
            'require_rating' => true,
            'send_notifications' => true,
        ];

        return view('admin.users.admin-features.system-settings', compact('settings'));
    }

    /**
     * Display full team view for admins.
     */
    public function teamView()
    {
        $departments = Department::with('users')->where('is_active', true)->get();
        $totalMembers = $departments->sum(fn ($department) => $department->users->count());
        $onlineCount = User::where('status', 'active')->count();
        $avgResponseTime = '12m';

        return view('admin.users.admin-features.team-view', compact('departments', 'totalMembers', 'onlineCount', 'avgResponseTime'));
    }
}