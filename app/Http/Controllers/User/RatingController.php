<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AgentRating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = AgentRating::with(['agent:id,name,avatar,department', 'ticket:id,ticket_number,title'])
            ->where('user_id', auth()->id());

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

        return view('user.ratings.index', compact('ratings'));
    }

    public function edit(AgentRating $rating)
    {
        if ($rating->user_id !== auth()->id()) {
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
        if ($rating->user_id !== auth()->id()) {
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
        if ($rating->user_id !== auth()->id()) {
            abort(403);
        }

        $rating->delete();

        return redirect()->route('user.ratings.index')
            ->with('success', 'Rating deleted successfully.');
    }
}
