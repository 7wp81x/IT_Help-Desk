<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AgentRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = AgentRating::with(['agent:id,name,avatar,department', 'ticket:id,ticket_number,title'])
            ->where('user_id', Auth::id());

        // Search by agent name, ticket number, or comment
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('agent', function ($agentQuery) use ($search) {
                    $agentQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('ticket', function ($ticketQuery) use ($search) {
                    $ticketQuery->where('ticket_number', 'like', "%{$search}%")
                               ->orWhere('title', 'like', "%{$search}%");
                })
                ->orWhere('comment', 'like', "%{$search}%");
            });
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Sort options
        $sortBy = $request->get('sort', 'recent');
        switch ($sortBy) {
            case 'recent':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'rating_high':
                $query->orderBy('rating', 'desc');
                break;
            case 'rating_low':
                $query->orderBy('rating', 'asc');
                break;
        }

        $ratings = $query->paginate(10);

        // Handle AJAX requests
        if ($request->expectsJson() || $request->input('ajax')) {
            $html = view('user.ratings.cards', compact('ratings'))->render();
            $pagination = $ratings->render();
            $resultsCount = view('user.ratings.results-count', compact('ratings'))->render();

            // Get updated statistics
            $allRatings = AgentRating::where('user_id', Auth::id())->get();

            $stats = [
                'total' => $allRatings->count(),
                '5star' => $allRatings->where('rating', 5)->count(),
                'comments' => $allRatings->whereNotNull('comment')->count(),
                'avg' => $allRatings->count() > 0 ? number_format($allRatings->avg('rating'), 1) : 'N/A',
            ];

            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'results_count' => $resultsCount,
                'stats' => $stats,
            ]);
        }

        return view('user.ratings.index', compact('ratings'));
    }

    public function edit(AgentRating $rating)
    {
        if ($rating->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if within 7 days
        if ($rating->created_at->diffInDays(now()) > 7) {
            return redirect()->route('user.ratings.index')
                ->with('error', 'You can only edit ratings within 7 days of submission.');
        }

        return view('user.ratings.edit', compact('rating'));
    }

    public function update(Request $request, AgentRating $rating)
    {
        if ($rating->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if within 7 days
        if ($rating->created_at->diffInDays(now()) > 7) {
            return redirect()->route('user.ratings.index')
                ->with('error', 'You can only edit ratings within 7 days of submission.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $rating->update($validated);

        return redirect()->route('user.ratings.index')
            ->with('success', 'Rating updated successfully.');
    }

    public function destroy(AgentRating $rating)
    {
        if ($rating->user_id !== Auth::id()) {
            abort(403);
        }

        $rating->delete();

        return redirect()->route('user.ratings.index')
            ->with('success', 'Rating deleted successfully.');
    }
}
