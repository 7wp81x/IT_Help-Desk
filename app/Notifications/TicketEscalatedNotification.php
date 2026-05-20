<?php

namespace App\Notifications;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketEscalatedNotification extends Notification
{

    public Ticket $ticket;
    public string $oldPriority;
    public string $newPriority;
    public ?User $actor;

    public function __construct(Ticket $ticket, string $oldPriority, string $newPriority, ?User $actor = null)
    {
        $this->ticket = $ticket;
        $this->oldPriority = $oldPriority;
        $this->newPriority = $newPriority;
        $this->actor = $actor;
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
        $actorName = $this->actor?->name ?? 'Support Team';
        $ticketUrl = $this->getUrlFor($notifiable);

        if ($notifiable->role === 'agent') {
            return (new MailMessage)
                ->subject('Ticket escalated: ' . $this->ticket->ticket_number)
                ->greeting('Hello ' . ($notifiable->name ?? ''))
                ->line('Ticket ' . $this->ticket->ticket_number . ' has been escalated from ' . ucfirst($this->oldPriority) . ' to ' . ucfirst($this->newPriority) . ' by ' . $actorName . '.')
                ->action('View Ticket', $ticketUrl)
                ->line('Please prioritize this ticket and update the requester as needed.');
        }

        return (new MailMessage)
            ->subject('Escalation alert: ' . $this->ticket->ticket_number)
            ->greeting('Hello ' . ($notifiable->name ?? ''))
            ->line('Ticket ' . $this->ticket->ticket_number . ' was escalated from ' . ucfirst($this->oldPriority) . ' to ' . ucfirst($this->newPriority) . ' by ' . $actorName . '.')
            ->action('View Ticket', $ticketUrl)
            ->line('Please review the escalation and act accordingly.');
    }

    public function toSms(object $notifiable): string
    {
        return sprintf(
            'Ticket %s escalated to %s by %s. Please review it in the portal.',
            $this->ticket->ticket_number,
            ucfirst($this->newPriority),
            $this->actor?->name ?? 'Support Team'
        );
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'ticket_escalated',
            'role' => $notifiable->role ?? 'agent',
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'title' => $this->ticket->title,
            'priority' => $this->ticket->priority,
            'old_priority' => $this->oldPriority,
            'new_priority' => $this->newPriority,
            'message' => 'Ticket escalated to ' . ucfirst($this->newPriority) . '.',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }

    protected function getUrlFor(object $notifiable): string
    {
        if ($notifiable->role === 'admin') {
            return url('/admin/tickets/' . $this->ticket->id);
        }

        if ($notifiable->role === 'agent') {
            return url('/agent/tickets/' . $this->ticket->id);
        }

        return url('/user/tickets/' . $this->ticket->id);
    }
}
