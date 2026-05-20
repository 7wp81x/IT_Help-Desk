<?php

namespace App\Http\Controllers\User;

use App\Models\Attachment;
use App\Models\Category;
use App\Models\Comment;
use App\Models\MessageNotification;
use App\Models\Ticket;
use App\Models\TicketLog;
use App\Models\User;
use App\Events\TicketStatusChanged;
use App\Notifications\NewTicketNotification;
use App\Notifications\TicketCommentedNotification;
use App\Notifications\TicketStatusChangedNotification;
use App\Notifications\TicketUpdatedNotification;
use App\Traits\CommentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends \App\Http\Controllers\Controller
{
    use CommentManager;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Ticket::with(['category', 'user', 'assignedTo'])
            ->where('user_id', Auth::id());

        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'pending') {
                $query->whereIn('status', [
                    Ticket::STATUS_PENDING,
                    Ticket::STATUS_PENDING_USER_RESPONSE,
                    Ticket::STATUS_PENDING_ADMIN_APPROVAL,
                ]);
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Handle search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Calculate stats
        $stats = [
            'total' => Ticket::where('user_id', Auth::id())->count(),
            'open' => Ticket::where('user_id', Auth::id())->whereIn('status', [Ticket::STATUS_OPEN, Ticket::STATUS_ASSIGNED])->count(),
            'pending' => Ticket::where('user_id', Auth::id())->whereIn('status', [Ticket::STATUS_PENDING, Ticket::STATUS_PENDING_USER_RESPONSE, Ticket::STATUS_PENDING_ADMIN_APPROVAL])->count(),
            'in_progress' => Ticket::where('user_id', Auth::id())->where('status', Ticket::STATUS_IN_PROGRESS)->count(),
            'resolved' => Ticket::where('user_id', Auth::id())->where('status', Ticket::STATUS_RESOLVED)->count(),
            'closed' => Ticket::where('user_id', Auth::id())->where('status', Ticket::STATUS_CLOSED)->count(),
            'canceled' => Ticket::where('user_id', Auth::id())->where('status', Ticket::STATUS_CANCELED)->count(),
        ];

        // Handle AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'html' => view('user.tickets.partials.table', compact('tickets'))->render(),
                'results_count' => "Showing {$tickets->firstItem()} to {$tickets->lastItem()} of {$tickets->total()} tickets",
                'stats' => $stats
            ]);
        }

        return view('user.tickets.index', compact('tickets', 'stats'));
    }

    public function create()
    {
        return view('user.tickets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:5120',
        ]);

        // Set default priority if not provided
        $priority = $validated['priority'] ?? 'medium';

        $ticket = Ticket::create([
            'subject' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $priority,
            'assigned_to' => null,
            'user_id' => Auth::id(),
            'status' => Ticket::STATUS_OPEN,
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

        // Notify the ticket creator with confirmation and notify all admins.
        Auth::user()->notify(new \App\Notifications\TicketCreatedNotification($ticket));

        $admins = User::where('role', 'admin')->get();
        /** @var User $admin */
        foreach ($admins as $admin) {
            $admin->notify(new NewTicketNotification($ticket));
        }

        return redirect()->route('user.tickets.show', $ticket)
            ->with('success', 'Ticket created successfully!');
    }
    

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        if (Auth::user()->isAdmin() || Auth::user()->isAgent()) {
            $ticket->load(['category', 'assignedTo', 'user', 'comments.user', 'comments.attachments', 'attachments']);
        } else {
            $ticket->load(['category', 'assignedTo', 'user', 'attachments']);
            $ticket->setRelation('comments', $ticket->comments()->where('is_internal', false)->with(['user', 'attachments'])->orderBy('created_at')->get());
        }

        return view('user.tickets.show', compact('ticket'));
    }

    public function comment(Request $request, Ticket $ticket)
    {
        $this->authorize('reply', $ticket);

        $validated = $request->validate([
            'content' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:5120',
        ]);

        $comment = $ticket->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'is_internal' => false,
        ]);

        // Generate unique message anchor
        $comment->update(['message_anchor' => $this->generateMessageAnchor($comment)]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('tickets/' . $ticket->id, 'public');
                Attachment::create([
                    'ticket_id' => $ticket->id,
                    'comment_id' => $comment->id,
                    'filename' => $file->getClientOriginalName(),
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'path' => $path,
                    'user_id' => Auth::id(),
                ]);
            }
        }

        $oldStatus = $ticket->status;
        $newStatus = $oldStatus;

        if ($ticket->status === Ticket::STATUS_RESOLVED) {
            $newStatus = Ticket::STATUS_REOPENED;
            $ticket->update(['status' => $newStatus, 'reopened_at' => now()]);
        } elseif ($ticket->status === Ticket::STATUS_PENDING_USER_RESPONSE) {
            $newStatus = Ticket::STATUS_IN_PROGRESS;
            $ticket->update(['status' => $newStatus]);
        }

        $ticket->logs()->create([
            'user_id' => Auth::id(),
            'action' => 'comment_added',
            'metadata' => [
                'comment_id' => $comment->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ],
        ]);

        if ($oldStatus !== $newStatus) {
            event(new TicketStatusChanged($ticket, $oldStatus, $newStatus));
        }

        // Notify assigned agent
        if ($ticket->assignedTo) {
            $this->createMessageNotification(
                $ticket->assignedTo,
                $ticket,
                $comment,
                Auth::user(),
                'new_message',
                Auth::user()->name . ' replied to ticket #' . $ticket->ticket_number
            );
            $ticket->assignedTo->notify(new TicketCommentedNotification($ticket, $comment, Auth::user()));
        }

        // Notify admins
        $admins = User::where('role', 'admin')->get();
        /** @var User $admin */
        foreach ($admins as $admin) {
            if ($admin->id !== Auth::id()) {
                $this->createMessageNotification(
                    $admin,
                    $ticket,
                    $comment,
                    Auth::user(),
                    'new_message',
                    Auth::user()->name . ' replied to ticket #' . $ticket->ticket_number
                );
                $admin->notify(new TicketCommentedNotification($ticket, $comment, Auth::user()));
            }
        }

        return back()->with('success', 'Comment added successfully!');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', [Ticket::STATUS_CLOSED, Ticket::STATUS_REOPENED]),
        ]);

        if ($validated['status'] === Ticket::STATUS_CLOSED && $ticket->status !== Ticket::STATUS_RESOLVED) {
            return back()->withErrors(['status' => 'Only resolved tickets can be closed by the user.']);
        }

        if ($validated['status'] === Ticket::STATUS_REOPENED && $ticket->status !== Ticket::STATUS_CLOSED) {
            return back()->withErrors(['status' => 'Only closed tickets can be reopened.']);
        }

        $oldStatus = $ticket->status;

        $ticket->update([
            'status' => $validated['status'],
            'closed_at' => $validated['status'] === Ticket::STATUS_CLOSED ? now() : null,
            'reopened_at' => $validated['status'] === Ticket::STATUS_REOPENED ? now() : null,
        ]);

        $ticket->logs()->create([
            'user_id' => Auth::id(),
            'action' => 'status_changed',
            'metadata' => [
                'old_status' => $oldStatus,
                'new_status' => $ticket->status,
            ],
        ]);

        if ($oldStatus !== $ticket->status) {
            event(new TicketStatusChanged($ticket, $oldStatus, $ticket->status));

            if ($ticket->assignedTo) {
                $ticket->assignedTo->notify(new TicketStatusChangedNotification($ticket, $oldStatus, $ticket->status, Auth::user()));
            }

            $admins = User::where('role', 'admin')->get();
            /** @var User $admin */
            foreach ($admins as $admin) {
                if ($admin->id !== Auth::id()) {
                    $admin->notify(new TicketStatusChangedNotification($ticket, $oldStatus, $ticket->status, Auth::user()));
                }
            }
        }

        return back()->with('success', 'Ticket status updated successfully!');
    }

    public function edit(Ticket $ticket)
    {
        if (Auth::id() !== $ticket->user_id) {
            abort(403);
        }

        if ($ticket->status !== 'in_progress') {
            return redirect()->route('user.tickets.show', $ticket)->withErrors(['edit' => 'You can only edit tickets that are in progress.']);
        }

        return view('user.tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        if (Auth::id() !== $ticket->user_id) {
            abort(403);
        }

        if ($ticket->status !== 'in_progress') {
            return redirect()->route('user.tickets.show', $ticket)->withErrors(['update' => 'You can only update tickets that are in progress.']);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:5120',
        ]);

        // Set default priority if not provided
        $priority = $validated['priority'] ?? 'medium';

        $ticket->update([
            'subject' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $priority,
        ]);

        $attachmentsAdded = false;

        if ($request->hasFile('attachments')) {
            $attachmentsAdded = true;
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

        $changedFields = [];
        foreach (['subject', 'description', 'priority'] as $field) {
            if ($ticket->wasChanged($field)) {
                $changedFields[] = $field;
            }
        }

        if (!empty($changedFields) || $attachmentsAdded) {
            if ($ticket->assignedTo) {
                $ticket->assignedTo->notify(new TicketUpdatedNotification($ticket, $changedFields, Auth::user(), $attachmentsAdded));
            }

            $admins = User::where('role', 'admin')->get();
            /** @var User $admin */
            foreach ($admins as $admin) {
                if ($admin->id !== Auth::id()) {
                    $admin->notify(new TicketUpdatedNotification($ticket, $changedFields, Auth::user(), $attachmentsAdded));
                }
            }
        }

        return redirect()->route('user.tickets.show', $ticket)->with('success', 'Ticket updated successfully!');
    }

    public function destroy(Ticket $ticket)
    {
        if (Auth::id() !== $ticket->user_id) {
            abort(403);
        }

        if (!in_array($ticket->status, ['resolved', 'closed', 'canceled'])) {
            return redirect()->route('user.tickets.show', $ticket)->withErrors(['delete' => 'You can only delete tickets after they are resolved, closed, or canceled.']);
        }

        $ticket->comments()->delete();
        $ticket->delete();

        return redirect()->route('user.tickets.index')->with('success', 'Ticket deleted successfully!');
    }

    public function cancel(Request $request, Ticket $ticket)
    {
        if (Auth::id() !== $ticket->user_id) {
            abort(403);
        }

        if ($ticket->status !== Ticket::STATUS_OPEN) {
            return redirect()->route('user.tickets.show', $ticket)->withErrors(['cancel' => 'Only open tickets can be canceled.']);
        }

        $oldStatus = $ticket->status;
        $ticket->update(['status' => Ticket::STATUS_CANCELED]);

        $ticket->comments()->create([
            'user_id' => Auth::id(),
            'content' => 'This ticket was canceled by the requester.',
            'is_internal' => false,
        ]);

        $ticket->logs()->create([
            'user_id' => Auth::id(),
            'action' => 'ticket_canceled',
            'metadata' => [
                'old_status' => $oldStatus,
                'new_status' => Ticket::STATUS_CANCELED,
            ],
        ]);

        event(new TicketStatusChanged($ticket, $oldStatus, Ticket::STATUS_CANCELED));

        if ($ticket->assignedTo) {
            $ticket->assignedTo->notify(new \App\Notifications\TicketStatusChangedNotification($ticket, $oldStatus, Ticket::STATUS_CANCELED, Auth::user()));
        }

        $admins = User::where('role', 'admin')->get();
        /** @var User $admin */
        foreach ($admins as $admin) {
            if ($admin->id !== Auth::id()) {
                $admin->notify(new \App\Notifications\TicketStatusChangedNotification($ticket, $oldStatus, Ticket::STATUS_CANCELED, Auth::user()));
            }
        }

        return redirect()->route('user.tickets.show', $ticket)->with('success', 'Ticket canceled successfully.');
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

        return response()->file($filePath, [
            'Content-Type' => $attachment->mime_type,
            'Content-Disposition' => 'inline; filename="' . $attachment->original_name . '"',
        ]);
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
            /** @var Ticket $ticket */
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

    /**
     * Delete a comment (AJAX endpoint)
     */
    public function deleteComment(Request $request, Comment $comment)
    {
        $ticket = $comment->ticket;
        
        // Check if comment exists and user can delete it
        if (!$comment || !$comment->canBeDeletedBy(Auth::user())) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if (!$this->performCommentDeletion($comment, Auth::user())) {
            return response()->json(['success' => false, 'message' => 'Unable to delete comment'], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully',
            'comment_id' => $comment->id,
        ]);
    }

    /**
     * Get new/updated comments for real-time refresh (AJAX endpoint)
     */
    public function refreshComments(Request $request, Ticket $ticket)
    {
        $this->authorize('reply', $ticket);

        $lastCheckedAt = $request->input('last_checked_at');
        $newComments = $this->getNewComments($ticket, $lastCheckedAt);

        return response()->json([
            'success' => true,
            'comments' => $newComments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'content' => $comment->trashed() ? 'This message was deleted' : $comment->content,
                    'is_deleted' => $comment->trashed(),
                    'user_name' => $comment->user->name,
                    'user_avatar' => $comment->user->avatar_url,
                    'created_at' => $comment->created_at->format('M d, h:i A'),
                    'message_anchor' => $comment->message_anchor,
                    'attachments' => $comment->attachments->map(fn($a) => [
                        'id' => $a->id,
                        'name' => $a->original_name,
                        'path' => route('user.tickets.download', $a),
                    ]),
                ];
            }),
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Mark message notification as read (AJAX endpoint)
     */
    public function markNotificationRead(Request $request, Comment $comment)
    {
        $this->markCommentNotificationAsRead($comment, Auth::user());
        
        return response()->json([
            'success' => true,
            'unread_count' => $this->getUnreadNotificationCount(Auth::user()),
        ]);
    }

    /**
     * Get unread notification count (AJAX endpoint)
     */
    public function getUnreadCount()
    {
        return response()->json([
            'unread_count' => $this->getUnreadNotificationCount(Auth::user()),
        ]);
    }
}