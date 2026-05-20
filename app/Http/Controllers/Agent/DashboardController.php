<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        $stats = [
            'assigned_tickets' => Ticket::where('assigned_to', $userId)->count(),
            'open_assigned' => Ticket::where('assigned_to', $userId)->where('status', 'open')->count(),
            'in_progress_count' => Ticket::where('assigned_to', $userId)->where('status', 'in_progress')->count(),
            'resolved_by_me' => Ticket::where('assigned_to', $userId)->where('status', 'resolved')->count(),
            'my_rating' => Auth::user()->rating ?? 0,
        ];
        
        $myTickets = Ticket::where('assigned_to', $userId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        return view('agent.dashboard', compact('stats', 'myTickets'));
    }

}