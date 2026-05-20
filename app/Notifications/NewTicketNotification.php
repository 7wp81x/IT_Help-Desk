<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTicketNotification extends Notification
{

    protected $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $role = $notifiable->role ?? 'admin';
        $route = $role === 'admin' 
            ? route('admin.tickets.show', $this->ticket->id)
            : route('agent.tickets.show', $this->ticket->id);

        return (new MailMessage)
            ->subject('New Ticket Created: #' . $this->ticket->ticket_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new ticket has been created by ' . $this->ticket->user->name)
            ->line('Subject: ' . $this->ticket->subject)
            ->line('Priority: ' . ucfirst($this->ticket->priority))
            ->action('View Ticket', $route)
            ->line('Please review and assign this ticket as soon as possible.');
    }

    public function toDatabase($notifiable)
    {
        $role = $notifiable->role ?? 'admin';
        
        return [
            'type' => 'new_ticket',
            'ticket_id' => $this->ticket->id,
            'title' => 'New Ticket Created',
            'message' => $this->ticket->user->name . ' created a new ticket: ' . $this->ticket->subject,
            'role' => $role,
            'ticket_number' => $this->ticket->ticket_number,
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}