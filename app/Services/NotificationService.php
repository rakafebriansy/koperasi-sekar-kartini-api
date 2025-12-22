<?php

namespace App\Services;

use App\Notifications\FcmNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function sendFcm($users, string $title, string $body, array $data = [])
    {
        try {
            $users = collect($users)
                ->filter(fn($user) => !empty($user->fcm_token));

            if ($users->isEmpty()) {
                Log::warning('FCM skipped: no users with fcm_token');
                return;
            }

            if (!isset($data['type'])) {
                throw new \InvalidArgumentException('FCM payload must contain type');
            }

            Notification::send(
                $users,
                new FcmNotification($title, $body, $data)
            );
        } catch (\Throwable $e) {
            Log::error('FCM send failed', [
                'error' => $e->getMessage(),
                'payload' => $data,
            ]);
        }
    }
}
