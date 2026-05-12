<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Response;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'submitted_by_name' => 'required|string|max:255',
            'submitted_by_email' => 'required|email',
            'submitted_by_phone' => 'nullable|string'
        ]);

        $ticket = Ticket::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ticket created successfully',
            'ticket_number' => $ticket->ticket_number,
            'ticket' => $ticket->load('category')
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