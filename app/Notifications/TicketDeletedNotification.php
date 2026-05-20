<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Ticket;
use App\Models\User;

class TicketDeletedNotification extends Notification
{
    use Queueable;

    protected Ticket $ticket;
    protected ?User $actor;

    public function __construct(Ticket $ticket, ?User $actor = null)
    {
        $this->ticket = $ticket;
        $this->actor = $actor;
    }

    public function via($notifiable)
    {
        $channels = ['mail', 'database'];

        if (!empty($notifiable->phone)) {
            $channels[] = 'sms';
        }

        return $channels;
    }

    public function toMail($notifiable)
    {
        $actorName = $this->actor ? $this->actor->name : 'System';

        return (new MailMessage)
                    ->subject('Your ticket was deleted')
                    ->greeting('Hello ' . ($notifiable->name ?? ''))
                    ->line("The ticket '{$this->ticket->subject}' (ID: #{$this->ticket->id}) has been deleted by {$actorName}.")
                    ->line('If you did not expect this, please contact support.')
                    ->action('View Helpdesk', url('/'));
    }

    public function toSms($notifiable): string
    {
        $actorName = $this->actor ? $this->actor->name : 'System';

        return "Your ticket '{$this->ticket->subject}' (ID: #{$this->ticket->id}) was deleted by {$actorName}. If you did not expect this, please contact support.";
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'ticket_deleted',
            'role' => 'user',
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'title' => 'Ticket deleted: ' . $this->ticket->subject,
            'ticket_subject' => $this->ticket->subject,
            'deleted_by' => $this->actor ? ['id' => $this->actor->id, 'name' => $this->actor->name] : null,
            'message' => "Your ticket '{$this->ticket->subject}' was deleted by " . ($this->actor ? $this->actor->name : 'System'),
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
