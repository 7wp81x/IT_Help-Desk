<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\TicketAssignment;
use App\Models\TicketSlaPolicy;
use Carbon\Carbon;

class TicketWorkflowService
{
    public function assignTicket(Ticket $ticket, int $agentId, int $assignerId, ?string $notes = null): TicketAssignment
    {
        return TicketAssignment::create([
            'ticket_id' => $ticket->id,
            'agent_id' => $agentId,
            'assigned_by' => $assignerId,
            'status' => 'pending',
            'notes' => $notes,
            'assigned_at' => now(),
        ]);
    }

    public function calculateSlaDates(Ticket $ticket): Ticket
    {
        if (! $ticket->slaPolicy) {
            return $ticket;
        }

        $ticket->response_due_at = now()->addMinutes($ticket->slaPolicy->response_time_minutes);
        $ticket->resolution_due_at = now()->addMinutes($ticket->slaPolicy->resolution_time_minutes);
        $ticket->last_activity_at = now();

        return $ticket;
    }

    public function escalateTicket(Ticket $ticket): Ticket
    {
        $ticket->status = Ticket::STATUS_ESCALATED;
        $ticket->escalated_at = now();
        return $ticket;
    }

    public function markResolved(Ticket $ticket): Ticket
    {
        $ticket->status = Ticket::STATUS_RESOLVED;
        $ticket->resolved_at = now();
        return $ticket;
    }

    public function markClosed(Ticket $ticket): Ticket
    {
        $ticket->status = Ticket::STATUS_CLOSED;
        $ticket->closed_at = now();
        return $ticket;
    }
}
