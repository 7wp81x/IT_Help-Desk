<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordLink extends Notification
{
    /**
     * The delivery method.
     *
     * @var string
     */
    public $method;

    /**
     * The password reset token.
     *
     * @var string
     */
    public string $token;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $token, string $method = 'mail')
    {
        $this->token = $token;
        $this->method = $method;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = [$this->method];

        if ($this->method === 'mail' && !empty($notifiable->phone)) {
            $channels[] = 'sms';
        }

        return array_unique($channels);
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reset Your Password')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', route('password.reset', ['token' => $this->token]))
            ->line('This password reset link will expire in 60 minutes.')
            ->line('If you did not request a password reset, no further action is required.')
            ->salutation('Best regards, IT Helpdesk Team');
    }

    /**
     * Get the SMS representation of the notification.
     */
    public function toSms(object $notifiable): string
    {
        $resetUrl = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->email,
        ]);

        return 'Hello ' . $notifiable->name . '! Reset your password: ' . $resetUrl . ' (expires in 60 minutes).';
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'password_reset',
            'role' => 'user',
            'reset_token' => $this->token,
        ];
    }
}