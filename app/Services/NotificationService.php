<?php

namespace App\Services;

use App\Notifications\FcmNotification;
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
        Notification::send(
            $users,
            new FcmNotification($title, $body, $data)
        );
    }
}
