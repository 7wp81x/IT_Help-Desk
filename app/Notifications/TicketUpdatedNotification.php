<?php

namespace App\Notifications;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketUpdatedNotification extends Notification
{

    public Ticket $ticket;
    public array $changedFields;
    public bool $attachmentsAdded;
    public ?User $actor;

    public function __construct(Ticket $ticket, array $changedFields = [], ?User $actor = null, bool $attachmentsAdded = false)
    {
        $this->ticket = $ticket;
        $this->changedFields = $changedFields;
        $this->actor = $actor;
        $this->attachmentsAdded = $attachmentsAdded;
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
        $updatedFields = $this->formatChangedFields();
        $ticketUrl = $this->getUrlFor($notifiable);

        $message = 'Ticket ' . $this->ticket->ticket_number . ' has been updated by ' . $actorName . '.';

        if ($updatedFields) {
            $message .= ' Updated fields: ' . $updatedFields . '.';
        }

        if ($this->attachmentsAdded) {
            $message .= ' New attachment(s) were added to the ticket.';
        }

        return (new MailMessage)
            ->subject('Ticket Updated: ' . $this->ticket->ticket_number)
            ->greeting('Hello ' . ($notifiable->name ?? ''))
            ->line($message)
            ->line('**Ticket Details:**')
            ->line('Number: ' . $this->ticket->ticket_number)
            ->line('Title: ' . $this->ticket->subject)
            ->line('Priority: ' . ucfirst($this->ticket->priority))
            ->line('Category: ' . ($this->ticket->category?->name ?? 'N/A'))
            ->action('View Ticket', $ticketUrl)
            ->line('You will receive further updates when the ticket status changes or a response is posted.');
    }

    public function toSms(object $notifiable): string
    {
        $updatedFields = $this->formatChangedFields();
        $message = 'Ticket ' . $this->ticket->ticket_number . ' updated by ' . ($this->actor?->name ?? 'Support Team') . '.';

        if ($updatedFields) {
            $message .= ' Fields: ' . $updatedFields . '.';
        }

        if ($this->attachmentsAdded) {
            $message .= ' Attachments were added.';
        }

        return $message;
    }

    public function toDatabase(object $notifiable): array
    {
        $updatedFields = $this->formatChangedFields();
        $message = 'Ticket ' . $this->ticket->ticket_number . ' was updated.';

        if ($updatedFields) {
            $message = 'Updated fields: ' . $updatedFields . '.';
        }

        if ($this->attachmentsAdded) {
            $message .= ' Attachments were added.';
        }

        return [
            'type' => 'ticket_updated',
            'role' => $notifiable->role ?? 'user',
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'title' => $this->ticket->subject,
            'priority' => $this->ticket->priority,
            'changed_fields' => $this->changedFields,
            'attachments_added' => $this->attachmentsAdded,
            'message' => $message,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }

    protected function formatChangedFields(): string
    {
        if (empty($this->changedFields)) {
            return '';
        }

        $labels = [
            'subject' => 'Title',
            'description' => 'Description',
            'priority' => 'Priority',
            'category_id' => 'Category',
            'user_id' => 'Requester',
            'status' => 'Status',
        ];

        return implode(', ', array_map(function ($field) use ($labels) {
            return $labels[$field] ?? ucfirst(str_replace('_', ' ', $field));
        }, $this->changedFields));
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
