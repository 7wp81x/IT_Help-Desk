<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class UserNotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:user');
    }
    
    public function index(Request $request)
    {
        $user = $request->user();
        
        $notifications = $user->notifications()
            ->latest()
            ->paginate(20);

        $unreadCount = $user->unreadNotifications()->count();
        $readCount = $user->notifications()->whereNotNull('read_at')->count();
        
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'notifications' => $notifications,
                'unread_count' => $unreadCount,
            ]);
        }

        return view('user.notifications.index', compact('notifications', 'unreadCount', 'readCount'));
    }

    public function show(Request $request, DatabaseNotification $notification)
    {
        $user = $request->user();

        if ($notification->notifiable_type !== get_class($user) || $notification->notifiable_id !== $user->getKey()) {
            abort(403);
        }

        if (!$notification->read_at) {
            $notification->markAsRead();
        }

        $data = $notification->data ?? [];
        $ticketId = data_get($data, 'ticket_id');

        if ($ticketId) {
            return redirect()->route('user.tickets.show', $ticketId);
        }

        return redirect()->route('user.dashboard');
    }

    public function markAllRead(Request $request)
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);
        return redirect()->route('user.notifications')->with('success', 'All notifications marked as read.');
    }

    public function deleteAll(Request $request)
    {
        $request->user()->notifications()->whereNotNull('read_at')->delete();
        return redirect()->route('user.notifications')->with('success', 'All read notifications cleared.');
    }
}