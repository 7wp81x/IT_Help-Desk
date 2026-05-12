<?php

namespace App\Http\Controllers\User;

use App\Models\Attachment;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends \App\Http\Controllers\Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Ticket::with(['category', 'user', 'assignedTo'])
            ->where('user_id', Auth::id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('user.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $categories = Category::all();

        // Get available agents with their ratings
        $agents = User::where('role', 'agent')
            ->with(['agentRatings', 'assignedTickets' => function($q) {
                $q->where('status', 'resolved');
            }])
            ->get()
            ->map(function($agent) {
                $agent->average_rating = $agent->agentRatings->avg('rating') ?? 0;
                $agent->total_resolved = $agent->assignedTickets->count();
                $agent->total_ratings = $agent->agentRatings->count();
                return $agent;
            })
            ->sortByDesc('average_rating');

        return view('user.tickets.create', compact('categories', 'agents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'category_id' => 'required|exists:categories,id',
            'assigned_to' => 'nullable|exists:users,id',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:5120',
        ]);

        // Set default priority if not provided
        $priority = $validated['priority'] ?? 'medium';

        $ticket = Ticket::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $priority,
            'category_id' => $validated['category_id'],
            'assigned_to' => $validated['assigned_to'] ?? null,
            'user_id' => Auth::id(),
            'status' => 'open',
            'ticket_number' => 'TKT-' . strtoupper(uniqid()),
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('tickets/' . $ticket->id, 'public');
                $ticket->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'path' => $path,
                    'user_id' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('user.tickets.show', $ticket)
            ->with('success', 'Ticket created successfully!');
    }

    public function show(Ticket $ticket)
    {
        if (Auth::id() !== $ticket->user_id) {
            abort(403);
        }

        $ticket->load(['category', 'assignedTo', 'user', 'comments.user', 'activities.user', 'attachments']);
        $categories = Category::all();

        return view('user.tickets.show', compact('ticket', 'categories'));
    }

    public function comment(Request $request, Ticket $ticket)
    {
        if (Auth::id() !== $ticket->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'content' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:5120',
        ]);

        $ticket->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'is_internal' => false,
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('tickets/' . $ticket->id, 'public');
                $ticket->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'path' => $path,
                    'user_id' => Auth::id(),
                ]);
            }
        }

        if ($ticket->status === 'resolved') {
            $ticket->update(['status' => 'open']);
        }

        return back()->with('success', 'Comment added successfully!');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        if (Auth::id() !== $ticket->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:open,closed',
        ]);

        if ($validated['status'] === 'closed' && $ticket->status !== 'resolved') {
            return back()->withErrors(['status' => 'Only resolved tickets can be closed by the user.']);
        }

        if ($validated['status'] === 'open' && $ticket->status !== 'closed') {
            return back()->withErrors(['status' => 'Only closed tickets can be reopened.']);
        }

        $ticket->update([
            'status' => $validated['status'],
            'closed_at' => $validated['status'] === 'closed' ? now() : null,
        ]);

        return back()->with('success', 'Ticket status updated successfully!');
    }

    public function downloadAttachment(Attachment $attachment)
    {
        $ticket = $attachment->ticket;

        if (Auth::id() !== $ticket->user_id) {
            abort(403);
        }

        $filePath = storage_path('app/public/' . $attachment->path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->download($filePath, $attachment->original_name);
    }

    public function rateAgent(Request $request, Ticket $ticket)
    {
        if (Auth::id() !== $ticket->user_id) {
            abort(403);
        }

        if ($ticket->status !== 'resolved') {
            return back()->withErrors(['rating' => 'You can only rate agents for resolved tickets.']);
        }

        if ($ticket->agentRating) {
            return back()->withErrors(['rating' => 'You have already rated this agent for this ticket.']);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $ticket->agentRating()->create([
            'user_id' => Auth::id(),
            'agent_id' => $ticket->assigned_to,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return back()->with('success', 'Thank you for rating the agent!');
    }

    public function exportCSV(Request $request)
    {
        $tickets = Ticket::with(['category', 'assignedTo'])
            ->where('user_id', Auth::id())
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('priority'), fn($q) => $q->where('priority', $request->priority))
            ->when($request->filled('category'), fn($q) => $q->where('category_id', $request->category))
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'my-tickets-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($tickets) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Ticket Number',
                'Subject',
                'Status',
                'Priority',
                'Category',
                'Agent',
                'Created Date',
                'Last Updated',
            ]);

            // CSV data
            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->ticket_number,
                    $ticket->title,
                    ucfirst(str_replace('_', ' ', $ticket->status)),
                    ucfirst($ticket->priority),
                    $ticket->category->name ?? '',
                    $ticket->assignedTo->name ?? 'Unassigned',
                    $ticket->created_at->format('Y-m-d H:i:s'),
                    $ticket->updated_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}