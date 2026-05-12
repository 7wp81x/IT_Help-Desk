<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $totalTickets = Ticket::count();
        $resolvedTickets = Ticket::where('status', 'resolved')->count();
        
        $ticketsByCategory = Category::withCount('tickets')->get();
        $ticketsByPriority = Ticket::selectRaw('priority, count(*) as count')->groupBy('priority')->get();
        
        $agentPerformance = User::where('role', 'agent')
            ->withCount('assignedTickets')
            ->get();
        
        return view('admin.reports.index', compact('totalTickets', 'resolvedTickets', 'ticketsByCategory', 'ticketsByPriority', 'agentPerformance'));
    }
}