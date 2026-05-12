<?php

namespace App\Http\Controllers\Agent;

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
        $this->middleware('role:agent');
    }

    public function index(Request $request)
    {
        $query = Ticket::with(['category', 'user', 'assignedTo'])
            ->where('assigned_to', Auth::id());

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

        return view('agent.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        if (Auth::id() !== $ticket->assigned_to) {
            abort(403);
        }

        $ticket->load(['category', 'assignedTo', 'user', 'comments.user', 'activities.user', 'attachments']);
        $categories = Category::all();
        $agents = User::where('role', 'agent')->get();

        return view('agent.tickets.show', compact('ticket', 'categories', 'agents'));
    }

    public function comment(Request $request, Ticket $ticket)
    {
        if (Auth::id() !== $ticket->assigned_to) {
            abort(403);
        }

        $validated = $request->validate([
            'content' => 'required|string',
            'is_internal' => 'sometimes|boolean',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:5120',
        ]);

        $ticket->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'is_internal' => $request->has('is_internal') ? true : false,
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

        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        return back()->with('success', 'Comment added successfully!');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        if (Auth::id() !== $ticket->assigned_to) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $updateData = ['status' => $validated['status']];

        if ($validated['status'] === 'resolved') {
            $updateData['resolved_at'] = now();
        } elseif ($validated['status'] === 'closed') {
            $updateData['closed_at'] = now();
        }

        $ticket->update($updateData);

        return back()->with('success', 'Ticket status updated successfully!');
    }

    public function downloadAttachment(Attachment $attachment)
    {
        $ticket = $attachment->ticket;

        if (Auth::id() !== $ticket->assigned_to) {
            abort(403);
        }

        $filePath = storage_path('app/public/' . $attachment->path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->download($filePath, $attachment->original_name);
    }

    public function assigned()
    {
        $tickets = Ticket::with(['category', 'user', 'assignedTo'])
            ->where('assigned_to', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('agent.tickets.index', compact('tickets'));
    }

    public function inProgress()
    {
        $tickets = Ticket::with(['category', 'user', 'assignedTo'])
            ->where('assigned_to', Auth::id())
            ->where('status', 'in_progress')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('agent.tickets.index', compact('tickets'));
    }

    public function resolved()
    {
        $tickets = Ticket::with(['category', 'user', 'assignedTo'])
            ->where('assigned_to', Auth::id())
            ->where('status', 'resolved')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('agent.tickets.index', compact('tickets'));
    }
}