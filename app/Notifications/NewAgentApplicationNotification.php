<?php

namespace App\Notifications;

use App\Models\AgentApplication;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAgentApplicationNotification extends Notification
{

    public AgentApplication $application;

    public function __construct(AgentApplication $application)
    {
        $this->application = $application;
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
            ->subject('New Agent Application Submitted')
            ->greeting('Hello ' . ($notifiable->name ?? 'Admin') . ',')
            ->line('A new agent application has been submitted and needs your review.')
            ->line('Applicant: ' . $this->application->full_name)
            ->line('Email: ' . $this->application->email)
            ->line('Phone: ' . ($this->application->phone ?? 'Not provided'))
            ->action('Review Application', route('admin.applications.show', $this->application->id))
            ->line('Please review the application and approve or reject it as soon as possible.');
    }

    public function toSms(object $notifiable): string
    {
        return sprintf(
            'New agent application: %s (%s). Review it in the admin portal.',
            $this->application->full_name,
            $this->application->email
        );
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'agent_application',
            'role' => 'admin',
            'application_id' => $this->application->id,
            'title' => 'New agent application submitted',
            'message' => $this->application->full_name . ' has applied to become an agent.',
            'applicant_name' => $this->application->full_name,
            'applicant_email' => $this->application->email,
            'applicant_phone' => $this->application->phone,
            'review_url' => route('admin.applications.show', $this->application->id),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
