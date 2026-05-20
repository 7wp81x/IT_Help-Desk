<?php

namespace App\Notifications;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketStatusChangedNotification extends Notification
{

    public Ticket $ticket;
    public string $oldStatus;
    public string $newStatus;
    public ?User $actor;

    public function __construct(Ticket $ticket, string $oldStatus, string $newStatus, ?User $actor = null)
    {
        $this->ticket = $ticket;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
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
        $statusLabel = $this->formatStatus($this->newStatus);
        $ticketUrl = $this->getUrlFor($notifiable);

        if ($notifiable->role === 'agent') {
            return (new MailMessage)
                ->subject('Ticket ' . $this->ticket->ticket_number . ' status updated')
                ->greeting('Hello ' . ($notifiable->name ?? ''))
                ->line('Ticket ' . $this->ticket->ticket_number . ' has changed status to ' . $statusLabel . ' by ' . $actorName . '.')
                ->line('**Previous status:** ' . $this->formatStatus($this->oldStatus))
                ->action('View Ticket', $ticketUrl)
                ->line('Please review the ticket and continue your response.');
        }

        if ($notifiable->role === 'admin') {
            return (new MailMessage)
                ->subject('Status update for ticket ' . $this->ticket->ticket_number)
                ->greeting('Hello ' . ($notifiable->name ?? ''))
                ->line('Ticket ' . $this->ticket->ticket_number . ' status changed from ' . $this->formatStatus($this->oldStatus) . ' to ' . $statusLabel . ' by ' . $actorName . '.')
                ->action('View Ticket', $ticketUrl)
                ->line('You can monitor ticket progress from the admin dashboard.');
        }

        return (new MailMessage)
            ->subject('Your ticket ' . $this->ticket->ticket_number . ' is now ' . $statusLabel)
            ->greeting('Hello ' . ($notifiable->name ?? ''))
            ->line('Your ticket status has been updated to ' . $statusLabel . ' by ' . $actorName . '.')
            ->action('View Ticket', $ticketUrl)
            ->line('We will keep you informed with any further changes.');
    }

    public function toSms(object $notifiable): string
    {
        return sprintf(
            'Ticket %s status changed to %s by %s.',
            $this->ticket->ticket_number,
            $this->formatStatus($this->newStatus),
            $this->actor?->name ?? 'Support Team'
        );
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'ticket_status_changed',
            'role' => $notifiable->role ?? 'user',
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'title' => $this->ticket->title,
            'priority' => $this->ticket->priority,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'message' => 'Ticket status changed from ' . $this->formatStatus($this->oldStatus) . ' to ' . $this->formatStatus($this->newStatus) . '.',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }

    protected function formatStatus(string $status): string
    {
        return ucfirst(str_replace('_', ' ', $status));
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
