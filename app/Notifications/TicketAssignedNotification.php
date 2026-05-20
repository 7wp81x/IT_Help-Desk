<?php

namespace App\Notifications;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketAssignedNotification extends Notification
{

    public Ticket $ticket;
    public ?User $assigner;

    public function __construct(Ticket $ticket, ?User $assigner = null)
    {
        $this->ticket = $ticket;
        $this->assigner = $assigner;
    }

    public function via(object $notifiable): array
    {
        $channels = ['mail', 'database'];

        if (!empty($notifiable->phone)) {
            $channels[] = 'sms';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $assignerName = $this->assigner ? $this->assigner->name : 'Administrator';
        $assignedAgentName = $this->ticket->assignedAgent?->name ?? 'an agent';

        if ($notifiable->role === 'agent') {
            return (new MailMessage)
                ->subject('Ticket Assigned: ' . $this->ticket->ticket_number)
                ->greeting('Hello ' . ($notifiable->name ?? ''))
                ->line('A ticket has been assigned to you by ' . $assignerName . '.')
                ->line('**Ticket Details:**')
                ->line('Number: ' . $this->ticket->ticket_number)
                ->line('Title: ' . $this->ticket->subject)
                ->line('Priority: ' . ucfirst($this->ticket->priority))
                ->line('Category: ' . ($this->ticket->category?->name ?? 'N/A'))
                ->line('Submitted by: ' . ($this->ticket->user?->name ?? 'Unknown'))
                ->action('View Ticket', url('/agent/tickets/' . $this->ticket->id))
                ->line('Please review the ticket and respond as soon as possible.')
                ->salutation('Best regards, IT Helpdesk System');
        }

        return (new MailMessage)
            ->subject('Ticket Assigned: ' . $this->ticket->ticket_number)
            ->greeting('Hello ' . ($notifiable->name ?? ''))
            ->line('Your ticket has been assigned to ' . $assignedAgentName . ' by ' . $assignerName . '.')
            ->line('**Ticket Details:**')
            ->line('Number: ' . $this->ticket->ticket_number)
            ->line('Title: ' . $this->ticket->subject)
            ->line('Priority: ' . ucfirst($this->ticket->priority))
            ->line('Category: ' . ($this->ticket->category?->name ?? 'N/A'))
            ->action('View Ticket', url('/user/tickets/' . $this->ticket->id))
            ->line('You will be notified when there are updates on this ticket.')
            ->salutation('Best regards, IT Helpdesk System');
    }

    public function toSms(object $notifiable): string
    {
        if ($notifiable->role === 'agent') {
            return sprintf(
                'Ticket %s assigned by %s. Priority: %s. Check the helpdesk portal to review it.',
                $this->ticket->ticket_number,
                $this->assigner?->name ?? 'Admin',
                ucfirst($this->ticket->priority)
            );
        }

        $assignedAgentName = $this->getAssignedAgentName();

        return sprintf(
            'Your ticket %s has been assigned to %s. You can view the status in the helpdesk portal.',
            $this->ticket->ticket_number,
            $assignedAgentName
        );
    }

    public function toDatabase(object $notifiable): array
    {
        $assignedAgentName = $this->getAssignedAgentName();

        return [
            'type' => 'ticket_assigned',
            'role' => $notifiable->role ?? 'agent',
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'title' => $this->ticket->subject,
            'priority' => $this->ticket->priority,
            'assigned_by' => $this->assigner ? ['id' => $this->assigner->id, 'name' => $this->assigner->name] : null,
            'assigned_agent' => ['id' => $this->ticket->assignedAgent?->id, 'name' => $assignedAgentName],
            'message' => $notifiable->role === 'agent'
                ? 'Ticket ' . $this->ticket->ticket_number . ' was assigned to you by ' . ($this->assigner?->name ?? 'Administrator') . '.'
                : 'Your ticket ' . $this->ticket->ticket_number . ' was assigned to ' . $assignedAgentName . '.',
        ];
    }

    protected function getAssignedAgentName(): string
    {
        return $this->ticket->assignedAgent?->name ?? 'an agent';
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
