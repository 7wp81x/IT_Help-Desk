<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Ticket;
use App\Models\User;

class TicketCancelledNotification extends Notification
{
    use Queueable;

    protected Ticket $ticket;
    protected ?User $actor;
    protected ?string $reason;

    public function __construct(Ticket $ticket, ?User $actor = null, ?string $reason = null)
    {
        $this->ticket = $ticket;
        $this->actor = $actor;
        $this->reason = $reason;
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
        $actorName = $this->actor ? $this->actor->name : 'Support Team';
        $message = (new MailMessage)
            ->subject('Your ticket has been canceled')
            ->greeting('Hello ' . ($notifiable->name ?? ''))
            ->line("Your ticket '{$this->ticket->subject}' (ID: #{$this->ticket->id}) has been canceled by {$actorName}.");

        if (!empty($this->reason)) {
            $message->line('Reason: ' . $this->reason);
        }

        return $message
            ->line('If you have questions about this cancellation, please reply or contact support.')
            ->action('View Helpdesk', url('/'));
    }

    public function toSms($notifiable): string
    {
        $actorName = $this->actor ? $this->actor->name : 'Support Team';
        $text = "Your ticket '{$this->ticket->subject}' (ID: #{$this->ticket->id}) was canceled by {$actorName}.";

        if (!empty($this->reason)) {
            $text .= ' Reason: ' . $this->reason;
        }

        return $text;
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'ticket_canceled',
            'role' => $notifiable->role ?? 'user',
            'ticket_id' => $this->ticket->id,
            'ticket_subject' => $this->ticket->subject,
            'canceled_by' => $this->actor ? ['id' => $this->actor->id, 'name' => $this->actor->name] : null,
            'reason' => $this->reason,
            'message' => "Your ticket '{$this->ticket->subject}' was canceled.",
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
