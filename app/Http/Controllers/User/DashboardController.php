<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        $stats = [
            'my_tickets' => Ticket::where('user_id', $userId)->count(),
            'open_tickets' => Ticket::where('user_id', $userId)->where('status', 'open')->count(),
            'in_progress_tickets' => Ticket::where('user_id', $userId)->where('status', 'in_progress')->count(),
            'resolved_tickets' => Ticket::where('user_id', $userId)->where('status', 'resolved')->count(),
            'closed_tickets' => Ticket::where('user_id', $userId)->where('status', 'closed')->count(),
        ];
        
        $myTickets = Ticket::where('user_id', $userId)
            ->with('assignedAgent')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('user.dashboard', compact('stats', 'myTickets'));
    }
}