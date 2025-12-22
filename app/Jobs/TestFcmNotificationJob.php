<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class TestFcmNotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected int $userId
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationService $notificationService): void
    {
        $user = User::find($this->userId);

        if (!$user || !$user->fcm_token) {
            return;
        }

        $notificationService->sendFcm(
            users: [$user],
            title: '🔔 Test Notifikasi',
            body: 'FCM berhasil diterima dari Laravel Queue',
            data: [
                'type' => 'test',
                'time' => now()->toIso8601String(),
            ]
        );
    }
}
