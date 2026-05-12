<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            $query->where('department', $request->department);
        }
        
        $users = $query->latest()->paginate(10)->withQueryString();
        
        $departments = User::whereNotNull('department')->distinct()->pluck('department');
        
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
    public function create()
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        return view('admin.users.create', compact('departments'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,agent,user',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'employee_id' => 'nullable|string|unique:users',
            'specialization' => 'nullable|string',
            'skills' => 'nullable|string',
            'status' => 'sometimes|in:active,inactive',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = $validated['status'] ?? 'active';

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

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
        return view('admin.users.edit', compact('user', 'departments'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,agent,user',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'employee_id' => 'nullable|string|unique:users,employee_id,' . $user->id,
            'specialization' => 'nullable|string',
            'skills' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|min:8|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->avatar && Storage::exists('avatars/' . $user->avatar)) {
            Storage::delete('avatars/' . $user->avatar);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Remove the specified user via AJAX.
     */
    public function destroyAjax(User $user)
    {
        if ($user->id === Auth::id()) {
            return response()->json(['success' => false, 'message' => 'You cannot delete your own account.']);
        }

        if ($user->avatar && Storage::exists('avatars/' . $user->avatar)) {
            Storage::delete('avatars/' . $user->avatar);
        }

        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
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
}