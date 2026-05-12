<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AgentRating;
use Illuminate\Http\Request;

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

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
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
        $userTicketsWithAgent = auth()->user()->tickets()
            ->where('assigned_to', $agent->id)
            ->with('agentRating')
            ->latest()
            ->get();

        return view('user.agents.show', compact('agent', 'stats', 'userTicketsWithAgent'));
    }
}
