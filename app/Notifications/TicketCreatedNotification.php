<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketCreatedNotification extends Notification
{

    public Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
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
        return (new MailMessage)
            ->subject('Ticket Created: ' . $this->ticket->ticket_number)
            ->greeting('Hello ' . ($notifiable->name ?? ''))
            ->line('Your support ticket has been successfully created.')
            ->line('**Ticket Details:**')
            ->line('Number: ' . $this->ticket->ticket_number)
            ->line('Title: ' . $this->ticket->subject)
            ->line('Priority: ' . ucfirst($this->ticket->priority))
            ->line('Category: ' . ($this->ticket->category?->name ?? 'N/A'))
            ->action('View Ticket', url('/user/tickets/' . $this->ticket->id))
            ->line('Our team has been notified and will review the ticket shortly.')
            ->salutation('Best regards, IT Helpdesk System');
    }

    public function toSms(object $notifiable): string
    {
        return sprintf(
            'Your ticket %s has been created successfully. Title: %s. You will receive updates from the helpdesk soon.',
            $this->ticket->ticket_number,
            $this->ticket->subject
        );
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'ticket_created',
            'role' => 'user',
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'title' => $this->ticket->subject,
            'priority' => $this->ticket->priority,
            'message' => 'Your ticket has been created successfully.',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
