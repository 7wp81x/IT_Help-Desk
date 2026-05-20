<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AgentRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = User::where('role', 'agent')
            ->with(['agentRatings', 'assignedTickets' => function($q) {
                $q->where('status', 'resolved');
            }]);

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by department
        if ($request->filled('department')) {
            $departmentValue = $request->department;
            if (is_numeric($departmentValue)) {
                $query->where('department_id', $departmentValue);
            } else {
                $query->whereHas('department', function ($q) use ($departmentValue) {
                    $q->where('name', $departmentValue);
                });
            }
        }

        // Filter by specialization
        if ($request->filled('specialization')) {
            $query->where('specialization', 'like', '%' . $request->specialization . '%');
        }

        // Sort options
        $sortBy = $request->get('sort', 'rating_desc');
        switch ($sortBy) {
            case 'rating_desc':
                $query->orderByRaw('(SELECT AVG(rating) FROM agent_ratings WHERE agent_id = users.id) DESC');
                break;
            case 'rating_asc':
                $query->orderByRaw('(SELECT AVG(rating) FROM agent_ratings WHERE agent_id = users.id) ASC');
                break;
            case 'resolved_desc':
                $query->orderByRaw('(SELECT COUNT(*) FROM tickets WHERE assigned_to = users.id AND status = "resolved") DESC');
                break;
            case 'resolved_asc':
                $query->orderByRaw('(SELECT COUNT(*) FROM tickets WHERE assigned_to = users.id AND status = "resolved") ASC');
                break;
            case 'response_time':
                $query->orderBy('avg_response_time', 'ASC');
                break;
            default:
                $query->orderByRaw('(SELECT AVG(rating) FROM agent_ratings WHERE agent_id = users.id) DESC');
        }

        $agents = $query->paginate(12);

        // Add computed attributes
        $agents->getCollection()->transform(function ($agent) {
            $agent->average_rating = $agent->agentRatings->avg('rating') ?? 0;
            $agent->total_resolved = $agent->assignedTickets->count();
            $agent->total_ratings = $agent->agentRatings->count();
            return $agent;
        });

        // Handle AJAX requests
        if ($request->expectsJson() || $request->input('ajax')) {
            $html = view('user.agents.cards', compact('agents'))->render();
            $pagination = $agents->render();
            $resultsCount = view('user.agents.results-count', compact('agents'))->render();

            // Get updated statistics
            $allAgents = User::where('role', 'agent')->with(['agentRatings', 'assignedTickets' => function($q) {
                $q->where('status', 'resolved');
            }])->get();

            $allAgents->transform(function ($agent) {
                $agent->average_rating = $agent->agentRatings->avg('rating') ?? 0;
                $agent->total_resolved = $agent->assignedTickets->count();
                $agent->total_ratings = $agent->agentRatings->count();
                return $agent;
            });

            $stats = [
                'total' => $allAgents->count(),
                'resolved' => number_format($allAgents->sum('total_resolved')),
                'rating' => number_format($allAgents->avg('average_rating') ?? 4.5, 1),
            ];

            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'results_count' => $resultsCount,
                'stats' => $stats,
            ]);
        }

        return view('user.agents.index', compact('agents'));
    }

    public function show(User $agent)
    {
        // Only show if user is an agent
        if (!$agent->isAgent()) {
            abort(404);
        }

        $agent->load([
            'agentRatings' => function($q) {
                $q->with('user:id,name,avatar')->latest()->limit(10);
            },
            'assignedTickets' => function($q) {
                $q->where('status', 'resolved')->with('user:id,name');
            }
        ]);

        // Calculate stats
        $stats = [
            'average_rating' => $agent->agentRatings->avg('rating') ?? 0,
            'total_resolved' => $agent->assignedTickets->count(),
            'total_ratings' => $agent->agentRatings->count(),
            'avg_response_time' => $agent->avg_response_time ?? 0,
        ];

        // Get user's past tickets with this agent
        $userTicketsWithAgent = Auth::user()->tickets()
            ->where('assigned_to', $agent->id)
            ->with('agentRating')
            ->latest()
            ->get();

        return view('user.agents.show', compact('agent', 'stats', 'userTicketsWithAgent'));
    }
}
