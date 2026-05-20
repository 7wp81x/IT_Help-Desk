<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Events\TicketAssigned;
use App\Events\TicketStatusChanged;
use App\Models\Comment;
use App\Models\MessageNotification;
use App\Models\Ticket;
use App\Models\Department;
use App\Models\TicketAssignment;
use App\Models\TicketLog;
use App\Models\User;
use App\Models\Attachment;
use App\Models\Category;
use App\Traits\CommentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Notifications\TicketAssignedNotification;
use App\Notifications\TicketCancelledNotification;
use App\Notifications\TicketCommentedNotification;
use App\Notifications\TicketEscalatedNotification;
use App\Notifications\TicketDeletedNotification;
use App\Notifications\TicketStatusChangedNotification;
use App\Notifications\TicketUpdatedNotification;

class TicketController extends Controller
{
    use CommentManager;
    // Main CRUD routes
    public function index()
    {
        $tickets = Ticket::with(['user', 'assignedAgent', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $categories = Category::all();
        $departments = Department::where('is_active', 1)->get();
        $users = User::where('role', 'user')->get();
        $agents = User::where('role', 'agent')->get();
        
        return view('admin.tickets.create', compact('categories', 'departments', 'users', 'agents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'user_id' => 'required|exists:users,id',
            'assigned_to' => 'nullable|exists:users,id',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240'
        ]);

        $validated['status'] = empty($validated['assigned_to']) ? Ticket::STATUS_OPEN : Ticket::STATUS_IN_PROGRESS;
        $validated['ticket_number'] = 'TKT-' . strtoupper(uniqid());
        $validated['department_id'] = Category::where('id', $validated['category_id'])->value('department_id');

        $ticket = Ticket::create($validated);

        // Handle attachments if any
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('tickets/' . $ticket->id, 'public');
                $ticket->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'path' => $path,
                    'user_id' => Auth::id()
                ]);
            }
        }

        $ticket->logs()->create([
            'user_id' => Auth::id(),
            'action' => 'ticket_created',
            'metadata' => [
                'assigned_to' => $ticket->assigned_to,
                'status' => $ticket->status,
            ],
        ]);

        if ($ticket->user) {
            $ticket->user->notify(new \App\Notifications\TicketCreatedNotification($ticket));
        }

        if (!empty($ticket->assigned_to)) {
            $agent = User::find($ticket->assigned_to);
            if ($agent) {
                Ticket::where('assigned_to', $agent->id)
                       ->where('id', '!=', $ticket->id)
                       ->whereIn('status', [Ticket::STATUS_OPEN, Ticket::STATUS_IN_PROGRESS])
                       ->update(['assigned_to' => null]);

                $ticket->assignments()->create([
                    'agent_id' => $agent->id,
                    'assigned_by' => Auth::id(),
                    'status' => 'pending',
                    'assigned_at' => now(),
                ]);

                $agent->notify(new TicketAssignedNotification($ticket, Auth::user()));
                event(new TicketAssigned($ticket));
            }
        }

        if ($ticket->assigned_to && $ticket->user) {
            $ticket->user->notify(new \App\Notifications\TicketAssignedNotification($ticket, Auth::user()));
        }

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', 'Ticket created successfully!');
    }

    public function show(int $id)
    {
        $ticket = Ticket::with([
            'user',
            'assignedAgent',
            'category',
            'responses.user',
            'attachments'
        ])->findOrFail($id);

        $agents = User::where('role', 'agent')->get();
        $departments = Department::where('is_active', 1)->get();
        $categories = Category::where('is_active', 1)->get();

        return view('admin.tickets.show', compact(
            'ticket',
            'agents',
            'departments',
            'categories'
        ));
    }

    public function edit(int $id)
    {
        $ticket = Ticket::findOrFail($id);
        $departments = Department::all();
        $categories = Category::all();
        $users = User::where('role', 'user')->get();
        $agents = User::where('role', 'agent')->get();
        
        return view('admin.tickets.edit', compact('ticket', 'departments', 'categories', 'users', 'agents'));
    }

    public function update(Request $request, int $id)
    {
        $ticket = Ticket::findOrFail($id);
        $oldStatus = $ticket->status;
        $oldAssignedTo = $ticket->assigned_to;

        $validated = $request->validate([
            'subject' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'category_id' => 'sometimes|exists:categories,id',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'status' => 'sometimes|in:' . implode(',', [
                Ticket::STATUS_OPEN,
                Ticket::STATUS_ASSIGNED,
                Ticket::STATUS_IN_PROGRESS,
                Ticket::STATUS_PENDING_USER_RESPONSE,
                Ticket::STATUS_PENDING_ADMIN_APPROVAL,
                Ticket::STATUS_ESCALATED,
                Ticket::STATUS_RESOLVED,
                Ticket::STATUS_CLOSED,
                Ticket::STATUS_CANCELED,
                Ticket::STATUS_REOPENED,
            ]),
            'assigned_to' => 'nullable|exists:users,id',
            'user_id' => 'sometimes|exists:users,id',
        ]);

        if (array_key_exists('category_id', $validated)) {
            $validated['department_id'] = Category::where('id', $validated['category_id'])->value('department_id');
        }

        $ticket->update($validated);

        if (array_key_exists('assigned_to', $validated)) {
            if (!empty($validated['assigned_to']) && $validated['assigned_to'] !== $oldAssignedTo) {
                $agent = User::find($validated['assigned_to']);
                if ($agent) {
                    Ticket::where('assigned_to', $agent->id)
                           ->where('id', '!=', $ticket->id)
                           ->whereIn('status', [Ticket::STATUS_OPEN, Ticket::STATUS_IN_PROGRESS])
                           ->update(['assigned_to' => null]);

                    if (in_array($ticket->status, [Ticket::STATUS_OPEN, Ticket::STATUS_ASSIGNED], true)) {
                        $ticket->update(['status' => Ticket::STATUS_IN_PROGRESS]);
                    }

                    $ticket->assignments()->create([
                        'agent_id' => $agent->id,
                        'assigned_by' => Auth::id(),
                        'status' => 'pending',
                        'assigned_at' => now(),
                    ]);

                    $ticket->logs()->create([
                        'user_id' => Auth::id(),
                        'action' => 'ticket_assigned',
                        'metadata' => [
                            'assigned_to' => $agent->id,
                            'old_status' => $oldStatus,
                            'new_status' => $ticket->status,
                        ],
                    ]);

                    $agent->notify(new TicketAssignedNotification($ticket, Auth::user()));
                    event(new TicketAssigned($ticket));
                }

                if ($ticket->user) {
                    $ticket->user->notify(new \App\Notifications\TicketAssignedNotification($ticket, Auth::user()));
                }
            }
        }

        $statusChanged = array_key_exists('status', $validated) && $oldStatus !== $ticket->status;
        if ($statusChanged) {
            if ($ticket->user && $ticket->user->id !== Auth::id()) {
                $ticket->user->notify(new TicketStatusChangedNotification($ticket, $oldStatus, $ticket->status, Auth::user()));
            }
            if ($ticket->assignedAgent && $ticket->assignedAgent->id !== Auth::id()) {
                $ticket->assignedAgent->notify(new TicketStatusChangedNotification($ticket, $oldStatus, $ticket->status, Auth::user()));
            }
        }

        $changedFields = [];
        foreach (['subject', 'description', 'category_id', 'priority', 'user_id'] as $field) {
            if ($ticket->wasChanged($field)) {
                $changedFields[] = $field;
            }
        }

        if (!empty($changedFields)) {
            if ($ticket->assignedAgent) {
                $ticket->assignedAgent->notify(new TicketUpdatedNotification($ticket, $changedFields, Auth::user()));
            }

            if ($ticket->user) {
                $ticket->user->notify(new TicketUpdatedNotification($ticket, $changedFields, Auth::user()));
            }
        }

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', 'Ticket updated successfully!');
    }

    public function destroy(int $id)
    {
        $ticket = Ticket::findOrFail($id);
        $owner = $ticket->user;

        // notify owner before deletion
        if ($owner) {
            try {
                $owner->notify(new TicketDeletedNotification($ticket, Auth::user()));
            } catch (\Throwable $e) {
                // swallow notification errors to avoid blocking deletion
            }
        }

        $ticket->delete(); // This will trigger booted method to delete attachments

        return redirect()->route('admin.tickets.all')
            ->with('success', 'Ticket deleted successfully!');
    }

    // Add this method
    public function all(Request $request)
    {
        $query = Ticket::with(['user', 'assignedAgent', 'category'])->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
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

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $tickets = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => Ticket::count(),
            'open' => Ticket::whereIn('status', [Ticket::STATUS_OPEN, Ticket::STATUS_ASSIGNED])->count(),
            'pending' => Ticket::whereIn('status', [Ticket::STATUS_PENDING, Ticket::STATUS_PENDING_USER_RESPONSE, Ticket::STATUS_PENDING_ADMIN_APPROVAL])->count(),
            'in_progress' => Ticket::where('status', Ticket::STATUS_IN_PROGRESS)->count(),
            'resolved' => Ticket::where('status', Ticket::STATUS_RESOLVED)->count(),
            'closed' => Ticket::where('status', Ticket::STATUS_CLOSED)->count(),
            'canceled' => Ticket::where('status', Ticket::STATUS_CANCELED)->count(),
        ];

        if ($request->ajax() || $request->has('ajax')) {
            return response()->json([
                'html' => view('admin.tickets.partials.table', compact('tickets'))->render(),
                'results_count' => "Showing " . ($tickets->firstItem() ?? 0) . " to " . ($tickets->lastItem() ?? 0) . " of " . $tickets->total() . " tickets",
                'stats' => $stats,
            ]);
        }

        return view('admin.tickets.all', compact('tickets', 'stats'));
    }

    // Status-based filters
    public function open()
    {
        $tickets = Ticket::with(['user', 'assignedAgent', 'category'])
            ->where('status', Ticket::STATUS_OPEN)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.tickets.open', compact('tickets'));
    }

    public function inProgress()
    {
        $tickets = Ticket::with(['user', 'assignedAgent', 'category'])
            ->where('status', Ticket::STATUS_IN_PROGRESS)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.tickets.in-progress', compact('tickets'));
    }

    public function resolved()
    {
        $tickets = Ticket::with(['user', 'assignedAgent', 'category'])
            ->where('status', Ticket::STATUS_RESOLVED)
            ->orderBy('resolved_at', 'desc')
            ->paginate(20);
        return view('admin.tickets.resolved', compact('tickets'));
    }

    public function closed()
    {
        $tickets = Ticket::with(['user', 'assignedAgent', 'category'])
            ->where('status', Ticket::STATUS_CLOSED)
            ->orderBy('closed_at', 'desc')
            ->paginate(20);
        return view('admin.tickets.closed', compact('tickets'));
    }

    // Assignment routes
    public function assignedToMe()
    {
        $tickets = Ticket::with(['user', 'assignedAgent', 'category'])
            ->where('assigned_to', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.tickets.assigned-to-me', compact('tickets'));
    }

    public function assignedByMe()
    {
        $tickets = Ticket::with(['user', 'assignedAgent', 'category'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.tickets.assigned-by-me', compact('tickets'));
    }

    // Ticket actions
    public function comment(Request $request, int $id)
    {
        $ticket = Ticket::findOrFail($id);
        $isInternal = $request->has('is_internal');

        if ($isInternal) {
            $this->authorize('addInternalNote', $ticket);
        } else {
            $this->authorize('reply', $ticket);
        }

        $validated = $request->validate([
            'message' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:5120',
        ]);

        $comment = $ticket->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['message'],
            'is_internal' => $isInternal,
        ]);

        // Generate unique message anchor
        $comment->update(['message_anchor' => $this->generateMessageAnchor($comment)]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('tickets/' . $ticket->id, 'public');
                \App\Models\Attachment::create([
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

        if ($ticket->status === Ticket::STATUS_OPEN) {
            $newStatus = Ticket::STATUS_IN_PROGRESS;
            $ticket->update(['status' => $newStatus]);
        }

        $ticket->logs()->create([
            'user_id' => Auth::id(),
            'action' => 'comment_added',
            'metadata' => [
                'comment_id' => $comment->id,
                'internal' => $isInternal,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ],
        ]);

        if ($oldStatus !== $newStatus) {
            event(new TicketStatusChanged($ticket, $oldStatus, $newStatus));
        }

        if ($isInternal) {
            if ($ticket->assignedAgent && $ticket->assignedAgent->id !== Auth::id()) {
                $this->createMessageNotification(
                    $ticket->assignedAgent,
                    $ticket,
                    $comment,
                    Auth::user(),
                    'internal_note',
                    'Internal note added to ticket #' . $ticket->ticket_number
                );
                $ticket->assignedAgent->notify(new TicketCommentedNotification($ticket, $comment, Auth::user()));
            }
        } else {
            if ($ticket->user && $ticket->user->id !== Auth::id()) {
                $this->createMessageNotification(
                    $ticket->user,
                    $ticket,
                    $comment,
                    Auth::user(),
                    'new_message',
                    Auth::user()->name . ' replied to ticket #' . $ticket->ticket_number
                );
                $ticket->user->notify(new TicketCommentedNotification($ticket, $comment, Auth::user()));
            }

            if ($ticket->assignedAgent && $ticket->assignedAgent->id !== Auth::id()) {
                $this->createMessageNotification(
                    $ticket->assignedAgent,
                    $ticket,
                    $comment,
                    Auth::user(),
                    'new_message',
                    Auth::user()->name . ' replied to ticket #' . $ticket->ticket_number
                );
                $ticket->assignedAgent->notify(new TicketCommentedNotification($ticket, $comment, Auth::user()));
            }

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
        }

        return back()->with('success', 'Comment added successfully!');
    }

    public function assign(Request $request, int $id)
    {
        $ticket = Ticket::findOrFail($id);
        $this->authorize('assign', $ticket);

        $validator = Validator::make($request->all(), [
            'department_id' => 'required|exists:departments,id',
            'category_id' => 'required|exists:categories,id',
            'assigned_to' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $agent = User::findOrFail($request->assigned_to);
        $oldStatus = $ticket->status;

        $ticket->fill([
            'department_id' => $request->department_id,
            'category_id' => $request->category_id,
            'assigned_to' => $agent->id,
            'status' => Ticket::STATUS_IN_PROGRESS,
        ])->save();

        Ticket::where('assigned_to', $agent->id)
               ->where('id', '!=', $ticket->id)
               ->whereIn('status', [Ticket::STATUS_OPEN, Ticket::STATUS_IN_PROGRESS])
               ->update(['assigned_to' => null]);

        $ticket->assignments()->create([
            'agent_id' => $agent->id,
            'assigned_by' => Auth::id(),
            'status' => 'pending',
            'assigned_at' => now(),
        ]);

        $ticket->logs()->create([
            'user_id' => Auth::id(),
            'action' => 'ticket_assigned',
            'metadata' => [
                'assigned_to' => $agent->id,
                'old_status' => $oldStatus,
                'new_status' => $ticket->status,
            ],
        ]);

        $agent->notify(new TicketAssignedNotification($ticket, Auth::user()));
        event(new TicketAssigned($ticket));

        if ($ticket->user) {
            $ticket->user->notify(new \App\Notifications\TicketAssignedNotification($ticket, Auth::user()));
        }

        return response()->json(['success' => true, 'message' => 'Ticket assigned successfully!']);
    }

    public function updatePriority(Request $request, int $id)
    {
        $request->validate([
            'priority' => 'required|in:low,medium,high,urgent'
        ]);

        $ticket = Ticket::findOrFail($id);
        $oldPriority = $ticket->priority;
        $ticket->update(['priority' => $request->priority]);

        $ticket->logs()->create([
            'user_id' => Auth::id(),
            'action' => 'priority_changed',
            'metadata' => [
                'old_priority' => $oldPriority,
                'new_priority' => $ticket->priority,
            ],
        ]);

        $changedFields = ['priority'];

        if ($ticket->user && $ticket->user->id !== Auth::id()) {
            $ticket->user->notify(new TicketUpdatedNotification($ticket, $changedFields, Auth::user()));
        }

        if ($ticket->assignedAgent && $ticket->assignedAgent->id !== Auth::id()) {
            $ticket->assignedAgent->notify(new TicketUpdatedNotification($ticket, $changedFields, Auth::user()));
        }

        return back()->with('success', 'Priority updated successfully!');
    }

    public function updateStatus(Request $request, int $id)
    {
        $ticket = Ticket::findOrFail($id);
        $this->authorize('reply', $ticket);

        $request->validate([
            'status' => 'required|in:' . implode(',', [
                Ticket::STATUS_OPEN,
                Ticket::STATUS_ASSIGNED,
                Ticket::STATUS_IN_PROGRESS,
                Ticket::STATUS_PENDING_USER_RESPONSE,
                Ticket::STATUS_PENDING_ADMIN_APPROVAL,
                Ticket::STATUS_ESCALATED,
                Ticket::STATUS_RESOLVED,
                Ticket::STATUS_CLOSED,
                Ticket::STATUS_CANCELED,
                Ticket::STATUS_REOPENED,
            ]),
        ]);

        $oldStatus = $ticket->status;
        $updateData = ['status' => $request->status];

        if ($request->status === Ticket::STATUS_RESOLVED) {
            $updateData['resolved_at'] = now();
        } elseif ($request->status === Ticket::STATUS_CLOSED) {
            $updateData['closed_at'] = now();
        } elseif ($request->status === Ticket::STATUS_ESCALATED) {
            $updateData['escalated_at'] = now();
        } elseif ($request->status === Ticket::STATUS_REOPENED) {
            $updateData['reopened_at'] = now();
        }

        $ticket->update($updateData);

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

            if ($ticket->user && $ticket->user->id !== Auth::id()) {
                $ticket->user->notify(new TicketStatusChangedNotification($ticket, $oldStatus, $ticket->status, Auth::user()));
            }
            if ($ticket->assignedAgent && $ticket->assignedAgent->id !== Auth::id()) {
                $ticket->assignedAgent->notify(new TicketStatusChangedNotification($ticket, $oldStatus, $ticket->status, Auth::user()));
            }
        }

        return back()->with('success', 'Status updated successfully!');
    }

    public function cancel(Request $request, int $id)
    {
        $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        $ticket = Ticket::findOrFail($id);
        $this->authorize('cancel', $ticket);
        $oldStatus = $ticket->status;

        $ticket->update(['status' => Ticket::STATUS_CANCELED]);

        $ticket->comments()->create([
            'user_id' => Auth::id(),
            'content' => 'Ticket canceled by admin. Reason: ' . ($request->reason ?? 'No reason provided.'),
            'is_internal' => false,
        ]);

        $ticket->logs()->create([
            'user_id' => Auth::id(),
            'action' => 'ticket_canceled',
            'metadata' => [
                'old_status' => $oldStatus,
                'reason' => $request->reason,
            ],
        ]);

        event(new TicketStatusChanged($ticket, $oldStatus, Ticket::STATUS_CANCELED));

        if ($ticket->user && $ticket->user->id !== Auth::id()) {
            $ticket->user->notify(new TicketCancelledNotification($ticket, Auth::user(), $request->reason));
        }

        if ($ticket->assignedAgent && $ticket->assignedAgent->id !== Auth::id()) {
            $ticket->assignedAgent->notify(new TicketStatusChangedNotification($ticket, $oldStatus, Ticket::STATUS_CANCELED, Auth::user()));
        }

        return back()->with('success', 'Ticket canceled and user notified successfully!');
    }

   public function escalate(int $id)
{
    $ticket = Ticket::findOrFail($id);
    $this->authorize('escalate', $ticket);

    $priorities = [
        Ticket::PRIORITY_LOW,
        Ticket::PRIORITY_MEDIUM,
        Ticket::PRIORITY_HIGH,
        Ticket::PRIORITY_URGENT
    ];

    $currentIndex = array_search($ticket->priority, $priorities, true);
    $oldPriority = $ticket->priority;
    $oldStatus = $ticket->status;

    if ($currentIndex !== false && $currentIndex < count($priorities) - 1) {
        $ticket->update([
            'priority' => $priorities[$currentIndex + 1]
        ]);
    }

    $ticket->update([
        'status' => Ticket::STATUS_ESCALATED,
        'escalated_at' => now()
    ]);

    $ticket->comments()->create([
        'user_id' => Auth::id(),
        'content' => 'Ticket escalated to ' . ucfirst($ticket->priority) . ' priority.',
        'is_internal' => true,
    ]);

    $ticket->logs()->create([
        'user_id' => Auth::id(),
        'action' => 'ticket_escalated',
        'metadata' => [
            'old_priority' => $oldPriority,
            'new_priority' => $ticket->priority,
            'old_status' => $oldStatus,
            'new_status' => $ticket->status,
        ],
    ]);

    event(new TicketStatusChanged($ticket, $oldStatus, $ticket->status));

    if ($ticket->assignedAgent && $ticket->assignedAgent->id !== Auth::id()) {
        $ticket->assignedAgent->notify(
            new TicketEscalatedNotification($ticket, $oldPriority, $ticket->priority, Auth::user())
        );
    }

    // FIXED ADMIN LOOP
    $admins = User::where('role', 'admin')->get();

    foreach ($admins as $admin) {
        if ($admin->id !== Auth::id()) {
            $admin->notify(
                new TicketEscalatedNotification($ticket, $oldPriority, $ticket->priority, Auth::user())
            );
        }
    }

    return back()->with('success', 'Ticket escalated successfully!');
}

    // Attachment routes
    public function downloadAttachment(int $id)
    {
        $attachment = Attachment::findOrFail($id);
        
        $ticket = $attachment->ticket;
        if (!Auth::user()->isAdmin() && Auth::id() !== $ticket->user_id && Auth::id() !== $ticket->assigned_to) {
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

    public function deleteAttachment(int $id)
    {
        $attachment = Attachment::findOrFail($id);
        
        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();

        return back()->with('success', 'Attachment deleted successfully!');
    }

    // Bulk actions
    public function bulkAssign(Request $request)
    {
        $request->validate([
            'ticket_ids' => 'required|array',
            'ticket_ids.*' => 'exists:tickets,id',
            'assigned_to' => 'required|exists:users,id'
        ]);

        $agent = User::findOrFail($request->assigned_to);
        $assigner = Auth::user();

        Ticket::whereIn('id', $request->ticket_ids)
            ->get()
            ->each(function (Ticket $ticket) use ($agent, $assigner) {
                $ticket->update(['assigned_to' => $agent->id, 'status' => Ticket::STATUS_ASSIGNED]);
                $ticket->assignments()->create([
                    'agent_id' => $agent->id,
                    'assigned_by' => $assigner->id,
                    'status' => 'pending',
                    'assigned_at' => now(),
                ]);

                $ticket->logs()->create([
                    'user_id' => $assigner->id,
                    'action' => 'ticket_bulk_assigned',
                    'metadata' => ['assigned_to' => $agent->id],
                ]);

                $agent->notify(new TicketAssignedNotification($ticket, $assigner));

                if ($ticket->user) {
                    $ticket->user->notify(new \App\Notifications\TicketAssignedNotification($ticket, $assigner));
                }
            });

        return back()->with('success', 'Tickets assigned successfully!');
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'ticket_ids' => 'required|array',
            'ticket_ids.*' => 'exists:tickets,id',
            'status' => 'required|in:' . implode(',', [
                Ticket::STATUS_OPEN,
                Ticket::STATUS_ASSIGNED,
                Ticket::STATUS_IN_PROGRESS,
                Ticket::STATUS_PENDING_USER_RESPONSE,
                Ticket::STATUS_PENDING_ADMIN_APPROVAL,
                Ticket::STATUS_ESCALATED,
                Ticket::STATUS_RESOLVED,
                Ticket::STATUS_CLOSED,
                Ticket::STATUS_CANCELED,
                Ticket::STATUS_REOPENED,
            ]),
        ]);

        $updateData = ['status' => $request->status];
        
        if ($request->status === Ticket::STATUS_RESOLVED) {
            $updateData['resolved_at'] = now();
        } elseif ($request->status === Ticket::STATUS_CLOSED) {
            $updateData['closed_at'] = now();
        } elseif ($request->status === Ticket::STATUS_ESCALATED) {
            $updateData['escalated_at'] = now();
        } elseif ($request->status === Ticket::STATUS_REOPENED) {
            $updateData['reopened_at'] = now();
        }

        Ticket::whereIn('id', $request->ticket_ids)
            ->get()
            ->each(function (Ticket $ticket) use ($updateData) {
                $oldStatus = $ticket->status;
                $ticket->update($updateData);

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

                    if ($ticket->user) {
                        $ticket->user->notify(new TicketStatusChangedNotification($ticket, $oldStatus, $ticket->status, Auth::user()));
                    }

                    if ($ticket->assignedAgent) {
                        $ticket->assignedAgent->notify(new TicketStatusChangedNotification($ticket, $oldStatus, $ticket->status, Auth::user()));
                    }
                }
            });

        return back()->with('success', 'Tickets status updated successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $data = $request->validate([
            'ticket_ids' => 'required|array',
            'ticket_ids.*' => 'exists:tickets,id'
        ]);

        Ticket::whereIn('id', $data['ticket_ids'])->chunkById(50, function ($tickets) {
            /** @var Ticket $ticket */
            foreach ($tickets as $ticket) {
                // notify owner
                $owner = $ticket->user;
                if ($owner) {
                    try {
                        $owner->notify(new TicketDeletedNotification($ticket, Auth::user()));
                    } catch (\Throwable $e) {
                        // continue even if notification fails
                    }
                }

                $ticket->delete();
            }
        });

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Tickets deleted successfully!']);
        }

        return back()->with('success', 'Tickets deleted successfully!');
    }

    // Export routes
    public function exportCSV()
    {
        $tickets = Ticket::with(['user', 'assignedAgent', 'category'])
            ->get();
        
        $filename = 'tickets-export-' . date('Y-m-d-His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($tickets) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['ID', 'Ticket #', 'Subject', 'Requester', 'Status', 'Priority', 'Category', 'Assigned To', 'Created Date']);
            
            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->id,
                    $ticket->ticket_number ?? 'N/A',
                    $ticket->subject,
                    $ticket->user->name ?? 'N/A',
                    $ticket->status,
                    $ticket->priority,
                    $ticket->category->name ?? 'N/A',
                    $ticket->assignedAgent->name ?? 'Unassigned',
                    $ticket->created_at->format('Y-m-d H:i')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function exportPDF()
    {
        return back()->with('info', 'PDF export coming soon!');
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
    public function refreshComments(Request $request, int $ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
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
                    'is_internal' => $comment->is_internal,
                    'attachments' => $comment->attachments->map(fn($a) => [
                        'id' => $a->id,
                        'name' => $a->original_name,
                        'path' => route('admin.tickets.download', $a->id),
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