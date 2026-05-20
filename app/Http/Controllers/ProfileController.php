<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the user profile page
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user statistics
        $stats = [
            'total_tickets' => $user->tickets()->count(),
            'open_tickets' => $user->tickets()->whereIn('status', ['open', 'in_progress', 'pending'])->count(),
            'resolved_tickets' => $user->tickets()->where('status', 'resolved')->count(),
            'closed_tickets' => $user->tickets()->where('status', 'closed')->count(),
            'comments_count' => $user->comments()->count(),
        ];
        
        // Get recent activities
        $recentActivities = $user->activities()
            ->with('ticket')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Get recent tickets
        $recentTickets = $user->tickets()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('profile.index', compact('user', 'stats', 'recentActivities', 'recentTickets'));
    }

    /**
     * Update user profile information
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'department_id' => 'nullable|exists:departments,id',
            'department' => 'nullable|string|max:255',
            'phone' => ['nullable', 'string', 'max:20', 'philippine_phone'],
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if (empty($request->input('department_id')) && $request->filled('department')) {
            $department = Department::where('name', $request->input('department'))->first();
            $user->department_id = $department ? $department->id : null;
        } else {
            $user->department_id = $request->input('department_id');
        }

        try {
            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                    Storage::disk('public')->delete('avatars/' . $user->avatar);
                }
                
                $avatar = $request->file('avatar');
                $avatarName = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();
                $avatar->storeAs('avatars', $avatarName, 'public');
                $user->avatar = $avatarName;
            }
            
            // Update user information
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();
            
            return redirect()->route('profile')
                ->with('success', 'Profile updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update profile. Please try again.');
        }
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->with('error', 'Current password is incorrect.');
        }
        
        // Update password
        $user->password = Hash::make($request->password);
        $user->save();
        
        return redirect()->route('profile')
            ->with('success', 'Password changed successfully!');
    }

    /**
     * Update user preferences (theme, notifications, etc.)
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'theme' => 'nullable|in:light,dark',
            'email_notifications' => 'boolean',
            'desktop_notifications' => 'boolean',
        ]);
        
        $preferences = $user->preferences ?? [];
        $preferences['theme'] = $request->theme ?? 'light';
        $preferences['email_notifications'] = $request->email_notifications ?? false;
        $preferences['desktop_notifications'] = $request->desktop_notifications ?? false;
        
        $user->preferences = $preferences;
        $user->save();
        
        return redirect()->route('profile')
            ->with('success', 'Preferences updated successfully!');
    }

    /**
     * Upload profile avatar via AJAX
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $user = Auth::user();
        
        try {
            // Delete old avatar
            if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }
            
            // Upload new avatar
            $avatar = $request->file('avatar');
            $avatarName = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();
            $avatar->storeAs('avatars', $avatarName, 'public');
            
            $user->avatar = $avatarName;
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Avatar updated successfully!',
                'avatar_url' => $user->avatar_url
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload avatar.'
            ], 500);
        }
    }

    /**
     * Delete user account (with confirmation)
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);
        
        $user = Auth::user();
        
        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()
                ->with('error', 'Password is incorrect.');
        }
        
        // Check if user has open tickets
        $openTickets = $user->tickets()->whereIn('status', ['open', 'in_progress', 'pending'])->count();
        if ($openTickets > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete account with open tickets. Please resolve or close them first.');
        }
        
        try {
            // Delete user avatar
            if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }
            
            // Delete user (cascade will handle related data if set in migrations)
            $user->delete();
            
            Auth::logout();
            
            return redirect()->route('welcome')
                ->with('success', 'Your account has been deleted successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete account. Please contact support.');
        }
    }

    /**
     * Get user notifications
     */
    public function getNotifications()
    {
        $user = Auth::user();
        
        // Get recent notifications (you can implement a Notification model)
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        $unreadCount = $user->unreadNotifications->count();
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markNotificationRead(int $id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->find($id);
        
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Export user data (GDPR compliance)
     */
    public function exportData()
    {
        $user = Auth::user();
        
        $data = [
            'user' => $user->toArray(),
            'tickets' => $user->tickets()->with(['category', 'comments', 'attachments'])->get()->toArray(),
            'comments' => $user->comments()->get()->toArray(),
            'activities' => $user->activities()->get()->toArray(),
            'exported_at' => now()->toDateTimeString(),
        ];
        
        $fileName = 'user_data_' . $user->id . '_' . now()->format('Ymd_His') . '.json';
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        
        return response($jsonData)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * Get user activity log
     */
    public function getActivityLog()
    {
        $user = Auth::user();
        
        $activities = $user->activities()
            ->with('ticket')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        if (request()->ajax()) {
            return response()->json($activities);
        }
        
        return view('profile.activities', compact('activities'));
    }

    /**
     * Update notification settings
     */
    public function updateNotificationSettings(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'ticket_created' => 'boolean',
            'ticket_updated' => 'boolean',
            'ticket_assigned' => 'boolean',
            'ticket_resolved' => 'boolean',
            'new_comment' => 'boolean',
        ]);
        
        $settings = $user->notification_settings ?? [];
        $settings['ticket_created'] = $request->ticket_created ?? false;
        $settings['ticket_updated'] = $request->ticket_updated ?? false;
        $settings['ticket_assigned'] = $request->ticket_assigned ?? false;
        $settings['ticket_resolved'] = $request->ticket_resolved ?? false;
        $settings['new_comment'] = $request->new_comment ?? false;
        
        $user->notification_settings = $settings;
        $user->save();
        
        return redirect()->route('profile')
            ->with('success', 'Notification settings updated successfully!');
    }

    /**
     * Get user statistics for profile
     */
    public function getStatistics()
    {
        $user = Auth::user();
        
        $stats = [
            'total_tickets' => $user->tickets()->count(),
            'open_tickets' => $user->tickets()->whereIn('status', ['open', 'in_progress', 'pending'])->count(),
            'resolved_tickets' => $user->tickets()->where('status', 'resolved')->count(),
            'closed_tickets' => $user->tickets()->where('status', 'closed')->count(),
            'total_comments' => $user->comments()->count(),
            'average_response_time' => $this->getAverageResponseTime($user),
            'tickets_by_priority' => $user->tickets()
                ->selectRaw('priority, count(*) as count')
                ->groupBy('priority')
                ->pluck('count', 'priority'),
            'tickets_by_month' => $user->tickets()
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, count(*) as count')
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->limit(6)
                ->get(),
        ];
        
        if (request()->ajax()) {
            return response()->json($stats);
        }
        
        return view('profile.statistics', compact('stats'));
    }

    /**
     * Calculate average response time for user's tickets
     */
    private function getAverageResponseTime(User $user)
    {
        $avgTime = $user->tickets()
            ->whereNotNull('resolved_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_hours')
            ->first()
            ->avg_hours;
        
        if ($avgTime) {
            $hours = floor($avgTime);
            $minutes = round(($avgTime - $hours) * 60);
            return "{$hours}h {$minutes}m";
        }
        
        return 'N/A';
    }
}