<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketCommentedNotification extends Notification
{

    public Ticket $ticket;
    public Comment $comment;
    public ?User $author;

    public function __construct(Ticket $ticket, Comment $comment, ?User $author = null)
    {
        $this->ticket = $ticket;
        $this->comment = $comment;
        $this->author = $author;
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
        $authorName = $this->author?->name ?? 'Support Team';
        $excerpt = $this->getCommentExcerpt();
        $ticketUrl = $this->getUrlFor($notifiable);

        if ($notifiable->role === 'agent') {
            return (new MailMessage)
                ->subject('New reply on ticket ' . $this->ticket->ticket_number)
                ->greeting('Hello ' . ($notifiable->name ?? ''))
                ->line('A new response was added to ticket ' . $this->ticket->ticket_number . ' by ' . $authorName . '.')
                ->line('**Comment summary:**')
                ->line($excerpt)
                ->action('View Ticket', $ticketUrl)
                ->line('Please review the response and follow up if needed.');
        }

        if ($notifiable->role === 'admin') {
            return (new MailMessage)
                ->subject('Ticket activity on ' . $this->ticket->ticket_number)
                ->greeting('Hello ' . ($notifiable->name ?? ''))
                ->line('Ticket ' . $this->ticket->ticket_number . ' received a new comment from ' . $authorName . '.')
                ->line('**Comment summary:**')
                ->line($excerpt)
                ->action('View Ticket', $ticketUrl)
                ->line('You can review the activity in the admin dashboard.');
        }

        return (new MailMessage)
            ->subject('Update on your ticket ' . $this->ticket->ticket_number)
            ->greeting('Hello ' . ($notifiable->name ?? ''))
            ->line('Your ticket has a new message from ' . $authorName . '.')
            ->line('**Comment summary:**')
            ->line($excerpt)
            ->action('View Ticket', $ticketUrl)
            ->line('We will keep you posted on any further updates.');
    }

    public function toSms(object $notifiable): string
    {
        $authorName = $this->author?->name ?? 'Support Team';

        return sprintf(
            'Ticket %s has a new comment from %s: "%s"',
            $this->ticket->ticket_number,
            $authorName,
            $this->getCommentExcerpt(60)
        );
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'ticket_commented',
            'role' => $notifiable->role ?? 'user',
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'title' => $this->ticket->title,
            'priority' => $this->ticket->priority,
            'comment_author' => $this->author?->name,
            'comment_excerpt' => $this->getCommentExcerpt(),
            'message' => 'New comment on ticket ' . $this->ticket->ticket_number . ' by ' . ($this->author?->name ?? 'Support Team') . '.',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }

    protected function getCommentExcerpt(int $length = 120): string
    {
        $excerpt = trim(preg_replace('/\s+/', ' ', $this->comment->content));

        if (strlen($excerpt) <= $length) {
            return $excerpt;
        }

        return substr($excerpt, 0, $length) . '...';
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
