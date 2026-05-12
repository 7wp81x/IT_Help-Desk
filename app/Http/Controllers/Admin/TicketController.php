<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Attachment;
use App\Models\Category;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
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
        $users = User::where('role', 'user')->get();
        $agents = User::where('role', 'agent')->get();
        
        return view('admin.tickets.create', compact('categories', 'users', 'agents'));
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

        $validated['status'] = 'open';
        $validated['ticket_number'] = 'TKT-' . strtoupper(uniqid());

        $user = User::find($validated['user_id']);
        if ($user && $user->department) {
            $department = Department::where('name', $user->department)->first();
            $validated['department_id'] = $department ? $department->id : null;
        }

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

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket created successfully!');
    }

    public function show(int $id)
    {
        $ticket = Ticket::with(['user', 'assignedAgent', 'category', 'responses.user', 'attachments'])
            ->findOrFail($id);
        
        $agents = User::where('role', 'agent')->get();
        
        return view('admin.tickets.show', compact('ticket', 'agents'));
    }

    public function edit(int $id)
    {
        $ticket = Ticket::findOrFail($id);
        $categories = Category::all();
        $users = User::where('role', 'user')->get();
        $agents = User::where('role', 'agent')->get();
        
        return view('admin.tickets.edit', compact('ticket', 'categories', 'users', 'agents'));
    }

    public function update(Request $request, int $id)
    {
        $ticket = Ticket::findOrFail($id);
        
        $validated = $request->validate([
            'subject' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'category_id' => 'sometimes|exists:categories,id',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'status' => 'sometimes|in:open,in_progress,resolved,closed',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $ticket->update($validated);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket updated successfully!');
    }

    public function destroy(int $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete(); // This will trigger booted method to delete attachments

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket deleted successfully!');
    }

    // Add this method
    public function all()
    {
        $tickets = Ticket::with(['user', 'assignedAgent', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.tickets.all', compact('tickets'));
}

    // Status-based filters
    public function open()
    {
        $tickets = Ticket::with(['user', 'assignedAgent', 'category'])
            ->where('status', 'open')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.tickets.open', compact('tickets'));
    }

    public function inProgress()
    {
        $tickets = Ticket::with(['user', 'assignedAgent', 'category'])
            ->where('status', 'in_progress')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.tickets.in-progress', compact('tickets'));
    }

    public function resolved()
    {
        $tickets = Ticket::with(['user', 'assignedAgent', 'category'])
            ->where('status', 'resolved')
            ->orderBy('resolved_at', 'desc')
            ->paginate(20);
        return view('admin.tickets.resolved', compact('tickets'));
    }

    public function closed()
    {
        $tickets = Ticket::with(['user', 'assignedAgent', 'category'])
            ->where('status', 'closed')
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
        $request->validate([
            'message' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:5120',
        ]);

        $ticket = Ticket::findOrFail($id);
        
        $ticket->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->message,
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

    public function assign(Request $request, int $id)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id'
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->update([
            'assigned_to' => $request->assigned_to,
            'status' => 'in_progress'
        ]);

        return back()->with('success', 'Ticket assigned successfully!');
    }

    public function updatePriority(Request $request, int $id)
    {
        $request->validate([
            'priority' => 'required|in:low,medium,high,urgent'
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->update(['priority' => $request->priority]);

        return back()->with('success', 'Priority updated successfully!');
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed'
        ]);

        $ticket = Ticket::findOrFail($id);
        
        $updateData = ['status' => $request->status];
        
        if ($request->status === 'resolved') {
            $updateData['resolved_at'] = now();
        } elseif ($request->status === 'closed') {
            $updateData['closed_at'] = now();
        }
        
        $ticket->update($updateData);

        return back()->with('success', 'Status updated successfully!');
    }

    public function escalate(int $id)
    {
        $ticket = Ticket::findOrFail($id);
        
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $currentIndex = array_search($ticket->priority, $priorities);
        
        if ($currentIndex !== false && $currentIndex < count($priorities) - 1) {
            $ticket->update(['priority' => $priorities[$currentIndex + 1]]);
        }
        
        $ticket->comments()->create([
            'user_id' => Auth::id(),
            'content' => 'Ticket escalated to ' . ucfirst($ticket->priority) . ' priority.',
            'is_internal' => true,
        ]);

        return back()->with('success', 'Ticket escalated successfully!');
    }
    

    // Attachment routes
    public function downloadAttachment(int $id)
    {
        $attachment = Attachment::findOrFail($id);
        
        $ticket = $attachment->ticket;
        if (!Auth::user()->hasRole('admin') && Auth::id() !== $ticket->user_id && Auth::id() !== $ticket->assigned_to) {
            abort(403);
        }
        
        $filePath = storage_path('app/public/' . $attachment->path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }
        
        return response()->download($filePath, $attachment->original_name);
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

        Ticket::whereIn('id', $request->ticket_ids)
            ->update(['assigned_to' => $request->assigned_to]);

        return back()->with('success', 'Tickets assigned successfully!');
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'ticket_ids' => 'required|array',
            'ticket_ids.*' => 'exists:tickets,id',
            'status' => 'required|in:open,in_progress,resolved,closed'
        ]);

        $updateData = ['status' => $request->status];
        
        if ($request->status === 'resolved') {
            $updateData['resolved_at'] = now();
        } elseif ($request->status === 'closed') {
            $updateData['closed_at'] = now();
        }

        Ticket::whereIn('id', $request->ticket_ids)
            ->update($updateData);

        return back()->with('success', 'Tickets status updated successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ticket_ids' => 'required|array',
            'ticket_ids.*' => 'exists:tickets,id'
        ]);

        Ticket::whereIn('id', $request->ticket_ids)->delete();

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
}