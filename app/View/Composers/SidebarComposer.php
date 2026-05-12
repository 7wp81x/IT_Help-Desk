<?php

namespace App\View\Composers;

use App\Models\Ticket;
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
        
        $view->with('ticketCounts', $ticketCounts);
    }
}