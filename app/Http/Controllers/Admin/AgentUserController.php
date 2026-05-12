<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketActivity;
use App\Models\AgentRating;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentUserController extends Controller
{
    /**
     * Display agents only
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'agent');
        
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
        
        $agents = $query->latest()->paginate(10)->withQueryString();
        
        $stats = [
            'agents' => User::where('role', 'agent')->count(),
            'online_agents' => User::where('role', 'agent')->where('status', 'active')->count(),
            'total_assigned' => Ticket::whereNotNull('assigned_to')->count(),
            'avg_response_time' => '2.5h',
        ];
        
        if ($request->ajax() || $request->has('ajax')) {
            $html = view('admin.users.partials.table', compact('agents'))->render();
            $resultsCount = "Showing " . $agents->firstItem() . " to " . $agents->lastItem() . " of " . $agents->total() . " results";
            
            return response()->json([
                'html' => $html,
                'results_count' => $resultsCount,
                'stats' => $stats
            ]);
        }

        return view('admin.users.tabs.agents', compact('agents', 'stats'));
    }

    /**
     * Display ticket assignments for agents.
     */
    public function assignments(Request $request)
    {
        $tickets = Ticket::whereNotNull('assigned_to')->latest()->paginate(10);

        $assignedCount = Ticket::whereNotNull('assigned_to')->count();
        $inProgressCount = Ticket::where('status', 'in_progress')->count();
        $resolvedThisWeek = Ticket::where('status', 'resolved')
            ->whereBetween('resolved_at', [Carbon::now()->subDays(7), Carbon::now()])
            ->count();
        $avgResponseTime = Ticket::whereNotNull('resolved_at')
            ->get()
            ->map(fn ($ticket) => $ticket->created_at->diffInHours($ticket->resolved_at))
            ->avg();

        return view('admin.users.agent-features.assignments', compact(
            'tickets',
            'assignedCount',
            'inProgressCount',
            'resolvedThisWeek',
            'avgResponseTime'
        ));
    }

    /**
     * Display agent performance metrics.
     */
    public function performance(Request $request)
    {
        $agent = Auth::user() && Auth::user()->role === 'agent'
            ? Auth::user()
            : User::where('role', 'agent')->first();

        if (!$agent) {
            return redirect()->route('admin.users.agents')->with('error', 'No agents available.');
        }

        $resolvedTickets = Ticket::where('assigned_to', $agent->id)->where('status', 'resolved');
        $totalResolved = $resolvedTickets->count();
        $resolvedThisWeek = $resolvedTickets->whereBetween('resolved_at', [Carbon::now()->subDays(7), Carbon::now()])->count();
        $avgRating = AgentRating::where('agent_id', $agent->id)->avg('rating') ?? 0;
        $totalRatings = AgentRating::where('agent_id', $agent->id)->count();

        $resolvedData = [];
        $responseTimeData = [];
        $chartLabels = [];
        for ($days = 6; $days >= 0; $days--) {
            $date = Carbon::now()->subDays($days);
            $chartLabels[] = $date->format('M d');
            $dayResolved = Ticket::where('assigned_to', $agent->id)
                ->where('status', 'resolved')
                ->whereDate('resolved_at', $date)
                ->count();
            $responseTime = Ticket::where('assigned_to', $agent->id)
                ->whereNotNull('resolved_at')
                ->whereDate('resolved_at', $date)
                ->get()
                ->map(fn ($ticket) => $ticket->created_at->diffInHours($ticket->resolved_at))
                ->avg() ?: 0;
            $resolvedData[] = $dayResolved;
            $responseTimeData[] = round($responseTime, 1);
        }

        $avgResolutionTime = Ticket::where('assigned_to', $agent->id)
            ->whereNotNull('resolved_at')
            ->get()
            ->map(fn ($ticket) => $ticket->created_at->diffInHours($ticket->resolved_at))
            ->avg() ?: 0;

        $fasterThanAvg = round(max(0, 20 - $avgResolutionTime)) . '%';
        $satisfactionScore = min(100, round(($avgRating / 5) * 100));

        $activities = TicketActivity::where('user_id', $agent->id)->latest()->limit(10)->get();

        return view('admin.users.agent-features.performance', compact(
            'agent',
            'totalResolved',
            'resolvedThisWeek',
            'avgRating',
            'totalRatings',
            'avgResolutionTime',
            'fasterThanAvg',
            'satisfactionScore',
            'activities',
            'chartLabels',
            'resolvedData',
            'responseTimeData'
        ));
    }

    /**
     * Display shift schedule for agents.
     */
    public function schedule(Request $request)
    {
        $week = Carbon::parse($request->query('week', now()));
        $startOfWeek = $week->startOfWeek();
        $dates = collect(range(0, 6))->map(fn ($index) => $startOfWeek->copy()->addDays($index)->format('M d'))->toArray();
        $schedule = collect(range(0, 6))->map(function ($day) use ($startOfWeek) {
            return [
                'shifts' => [
                    [
                        'type' => 'morning',
                        'start' => '08:00',
                        'end' => '16:00',
                        'title' => 'Morning Support',
                        'is_today' => $startOfWeek->copy()->addDays($day)->isToday(),
                    ],
                ],
            ];
        })->toArray();

        $weekRange = $startOfWeek->format('M d') . ' - ' . $startOfWeek->copy()->addDays(6)->format('M d');

        if ($request->ajax()) {
            return response()->json(['weekRange' => $weekRange, 'dates' => $dates, 'schedule' => $schedule]);
        }

        return view('admin.users.agent-features.schedule-view', compact('weekRange', 'dates', 'schedule'));
    }

    /**
     * Display agent team view (read-only).
     */
    public function teamView()
    {
        $agents = User::where('role', 'agent')->get()->map(function ($agent) {
            $agent->is_online = $agent->status === 'active';
            $agent->resolved_count = Ticket::where('assigned_to', $agent->id)->where('status', 'resolved')->count();
            $agent->experience = $agent->position ?? '3+ years';
            $agent->languages = 'English';
            $agent->rating = $agent->rating ?? 4.5;
            return $agent;
        });

        $totalAgents = $agents->count();
        $onlineCount = $agents->where('is_online', true)->count();
        $avgTeamRating = round($agents->avg('rating') ?: 0, 1);
        $totalResolved = $agents->sum('resolved_count');

        return view('admin.users.agent-features.team-view', compact('agents', 'totalAgents', 'onlineCount', 'avgTeamRating', 'totalResolved'));
    }
}