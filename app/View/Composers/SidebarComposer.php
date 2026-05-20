<?php

namespace App\View\Composers;

use App\Models\Announcement;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SidebarComposer
{
    public function compose(View $view)
    {
        // Get real counts from database
        $ticketCounts = [
            'all' => Ticket::count(),
            'open' => Ticket::where('status', 'open')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'resolved' => Ticket::where('status', 'resolved')->count(),
            'closed' => Ticket::where('status', 'closed')->count(),
        ];

        $notificationCount = 0;
        $announcementCount = 0;

        if ($user = Auth::user()) {
            $notificationCount = $user->unreadNotifications()
                ->where(function ($query) use ($user) {
                    $query->where('data->role', $user->role)
                          ->orWhereNull('data->role');
                })
                ->count();
            $announcementCount = Announcement::active()
                ->forAudience($user->role)
                ->whereDoesntHave('reads', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->count();
        }
        
        $view->with([
            'ticketCounts' => $ticketCounts,
            'notificationCount' => $notificationCount,
            'announcementCount' => $announcementCount,
        ]);
    }
}