<?php

namespace App\Services;

use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\User;

class NotificationService
{
    public function __construct(
        private Messaging $messaging
    ) {}

    public function sendToUser(User $user, string $title, string $body, array $data = []): void
    {
        if (!$user->fcm_token) {
            return;
        }

        try {
          \Log::info('FCM Step 1: Preparing cloud message', ['user_id' => $user->id]);

            $message = CloudMessage::withTarget('token', $user->fcm_token)
                ->withNotification(Notification::create($title, $body))
                ->withData($data);

            \Log::info('FCM Step 2: Message created successfully, sending to Firebase...', ['user_id' => $user->id]);

            $this->messaging->send($message);

            \Log::info('FCM Step 3: Notification sent successfully!', ['user_id' => $user->id]);
        } catch (\Throwable $e) {

            \Log::warning('FCM notification failed', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
        }
    }
}
