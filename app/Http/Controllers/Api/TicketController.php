<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function store(Request $request)
    {
        if (! Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required to create a ticket.',
            ], 401);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'nullable|in:low,medium,high,urgent',
        ]);

        $ticket = Ticket::create([
            'subject' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'] ?? 'medium',
            'user_id' => Auth::id(),
            'status' => Ticket::STATUS_OPEN,
            'ticket_number' => 'TKT-' . strtoupper(uniqid()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ticket created successfully',
            'ticket_number' => $ticket->ticket_number,
            'ticket' => $ticket->load('category', 'department')
        ], 201);
    }

    public function show($ticket_number)
    {
        $ticket = Ticket::with(['category', 'responses'])
            ->where('ticket_number', $ticket_number)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $ticket
        ]);
    }

    public function addResponse(Request $request, $ticket_number)
    {
        $ticket = Ticket::where('ticket_number', $ticket_number)->firstOrFail();

        $validated = $request->validate([
            'message' => 'required|string',
            'responder_name' => 'required|string'
        ]);

        $response = Response::create([
            'ticket_id' => $ticket->id,
            'message' => $validated['message'],
            'responder_type' => 'user',
            'responder_name' => $validated['responder_name']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Response added successfully',
            'data' => $response
        ]);
    }
}