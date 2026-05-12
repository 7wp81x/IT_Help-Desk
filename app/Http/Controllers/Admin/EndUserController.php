<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketActivity;
use App\Models\AgentRating;
use Illuminate\Http\Request;

class EndUserController extends Controller
{
    /**
     * Display end users only
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'user');
        
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
        
        $users = $query->latest()->paginate(10)->withQueryString();
        
        $stats = [
            'users' => User::where('role', 'user')->count(),
            'active_users' => User::where('role', 'user')->where('status', 'active')->count(),
            'user_tickets' => Ticket::whereHas('user', function($q) {
                $q->where('role', 'user');
            })->count(),
            'avg_rating' => 4.2,
            'resolution_rate' => 78,
        ];
        
        if ($request->ajax() || $request->has('ajax')) {
            $html = view('admin.users.partials.table', compact('users'))->render();
            $resultsCount = "Showing " . $users->firstItem() . " to " . $users->lastItem() . " of " . $users->total() . " results";
            
            return response()->json([
                'html' => $html,
                'results_count' => $resultsCount,
                'stats' => $stats
            ]);
        }

        return view('admin.users.tabs.users', compact('users', 'stats'));
    }

    /**
     * Display ticket history for selected end user.
     */
    public function ticketHistory(Request $request, User $selectedUser = null)
    {
        $users = User::where('role', 'user')->orderBy('name')->get();
        $tickets = collect();
        $openCount = $inProgressCount = $resolvedCount = 0;

        if ($selectedUser && $selectedUser->role === 'user') {
            $tickets = Ticket::where('user_id', $selectedUser->id)->latest()->paginate(10);
            $openCount = Ticket::where('user_id', $selectedUser->id)->where('status', 'open')->count();
            $inProgressCount = Ticket::where('user_id', $selectedUser->id)->where('status', 'in_progress')->count();
            $resolvedCount = Ticket::where('user_id', $selectedUser->id)->where('status', 'resolved')->count();
        }

        return view('admin.users.user-features.ticket-history', compact('users', 'selectedUser', 'tickets', 'openCount', 'inProgressCount', 'resolvedCount'));
    }

    /**
     * Display feedback from users.
     */
    public function feedback()
    {
        $feedbacks = AgentRating::with('user', 'ticket')->latest()->paginate(10);
        $totalRatings = AgentRating::count();
        $avgRating = AgentRating::avg('rating') ?: 0;
        $positiveCount = AgentRating::where('rating', '>=', 4)->count();
        $negativeCount = AgentRating::where('rating', '<', 4)->count();
        $positivePercent = $totalRatings ? round(($positiveCount / $totalRatings) * 100) : 0;
        $negativePercent = $totalRatings ? round(($negativeCount / $totalRatings) * 100) : 0;

        return view('admin.users.user-features.feedback', compact('feedbacks', 'totalRatings', 'avgRating', 'positivePercent', 'negativePercent'));
    }

    /**
     * Display activity log for selected end user.
     */
    public function activity(Request $request, User $selectedUser = null)
    {
        $users = User::where('role', 'user')->orderBy('name')->get();
        $activities = collect();

        if ($selectedUser && $selectedUser->role === 'user') {
            $activities = TicketActivity::with('user')
                ->where('user_id', $selectedUser->id)
                ->latest()
                ->paginate(10);
        }

        return view('admin.users.user-features.activity', compact('users', 'selectedUser', 'activities'));
    }

    /**
     * Display support agent view for end users.
     */
    public function supportView()
    {
        $availableAgents = User::where('role', 'agent')->where('status', 'active')->get();
        $offlineAgents = User::where('role', 'agent')->where('status', 'inactive')->get();

        return view('admin.users.user-features.support-view', compact('availableAgents', 'offlineAgents'));
    }
}