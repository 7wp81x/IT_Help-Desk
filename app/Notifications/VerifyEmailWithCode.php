<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmailWithCode extends Notification
{

    /**
     * The verification code.
     *
     * @var string
     */
    public $verificationCode;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $verificationCode)
    {
        $this->verificationCode = $verificationCode;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['mail'];

        if ($notifiable->phone) {
            $channels[] = 'sms';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Verify Your Email Address')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for registering with our IT Helpdesk System.')
            ->line('Please use the following code to verify your email address:')
            ->line('')
            ->line('**' . $this->verificationCode . '**')
            ->line('')
            ->line('This code will expire in 30 minutes.')
            ->action('Enter Verification Code', route('verification.notice'))
            ->line('')
            ->line('If you did not create this account, please ignore this email.')
            ->salutation('Best regards, IT Helpdesk Team');
    }

    /**
     * Get the SMS representation of the notification.
     */
    public function toSms(object $notifiable): string
    {
        return 'Hello ' . $notifiable->name . '! Your verification code for IT Helpdesk System is: ' . $this->verificationCode . '. This code will expire in 30 minutes.';
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'verify_email',
            'role' => 'user',
            'verification_code' => $this->verificationCode,
        ];
    }
}
