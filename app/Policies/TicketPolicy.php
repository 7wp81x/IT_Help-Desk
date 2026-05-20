<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isAgent()) {
            return $ticket->assigned_to === $user->id || ($user->department_id && $ticket->department_id === $user->department_id);
        }

        return $ticket->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isUser() || $user->isAdmin();
    }

    public function assign(User $user, Ticket $ticket): bool
    {
        return $user->isAdmin();
    }

    public function accept(User $user, Ticket $ticket): bool
    {
        return $user->isAgent() && $ticket->assigned_to === $user->id && $ticket->status === Ticket::STATUS_ASSIGNED;
    }

    public function reject(User $user, Ticket $ticket): bool
    {
        return $user->isAgent() && $ticket->assigned_to === $user->id && $ticket->status === Ticket::STATUS_ASSIGNED;
    }

    public function reply(User $user, Ticket $ticket): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isAgent()) {
            return $ticket->assigned_to === $user->id;
        }

        return $ticket->user_id === $user->id;
    }

    public function addInternalNote(User $user, Ticket $ticket): bool
    {
        return $user->isAdmin() || ($user->isAgent() && $ticket->assigned_to === $user->id);
    }

    public function resolve(User $user, Ticket $ticket): bool
    {
        return $user->isAgent() && $ticket->assigned_to === $user->id && in_array($ticket->status, [Ticket::STATUS_ASSIGNED, Ticket::STATUS_IN_PROGRESS, Ticket::STATUS_PENDING_ADMIN_APPROVAL], true);
    }

    public function escalate(User $user, Ticket $ticket): bool
    {
        return ($user->isAgent() && $ticket->assigned_to === $user->id) || $user->isAdmin();
    }

    public function transfer(User $user, Ticket $ticket): bool
    {
        return $user->isAdmin() || ($user->isAgent() && $ticket->assigned_to === $user->id);
    }

    public function close(User $user, Ticket $ticket): bool
    {
        if ($user->isAdmin()) {
            return in_array($ticket->status, [Ticket::STATUS_RESOLVED, Ticket::STATUS_PENDING_USER_RESPONSE, Ticket::STATUS_ESCALATED], true);
        }

        return $user->isUser() && $ticket->user_id === $user->id && $ticket->status === Ticket::STATUS_RESOLVED;
    }

    public function reopen(User $user, Ticket $ticket): bool
    {
        return ($user->isAdmin() || ($user->isUser() && $ticket->user_id === $user->id)) && $ticket->status === Ticket::STATUS_CLOSED;
    }

    public function cancel(User $user, Ticket $ticket): bool
    {
        return $user->isAdmin();
    }
}
