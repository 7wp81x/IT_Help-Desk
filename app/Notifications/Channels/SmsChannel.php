<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        if (!method_exists($notification, 'toSms')) {
            return;
        }

        $message = $notification->toSms($notifiable);

        if (!$message || !isset($notifiable->phone)) {
            return;
        }

        $this->sendSms($notifiable->phone, $message);
    }

    protected function sendSms(string $phone, string $message): bool
    {
        $apiUrl = config('services.sms.api_url');
        $apiKey = config('services.sms.api_key');
        $from = config('services.sms.from');

        if (!$apiUrl || !$apiKey) {
            return false;
        }

        try {
            $payload = [
                'apikey' => $apiKey,
                'number' => $phone,
                'message' => $message,
            ];

            if ($from) {
                $payload['sendername'] = $from;
            }

            $response = Http::acceptJson()
                ->post($apiUrl, $payload);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::warning('SMS notification failed', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}