<?php

namespace App\Jobs;

use App\Models\Meeting;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendMeetingReminderJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationService $notify)
    {
        Log::info('SendMeetingReminderJob is running...');
        $meetings = Meeting::whereNull('reminder_sent_at')
            ->whereBetween('datetime', [
                now(),
                now()->addHours(24)->addMinutes(1),
            ])
            ->get();
        Log::info('SendMeetingReminderJob has ' . count($meetings) . ' meetings on queue.');

        foreach ($meetings as $meeting) {

            $members = $meeting->type === 'group'
                ? $meeting->group?->members
                : \App\Models\User::all();

            Log::info($meeting->name . ' has ' . count($members) . ' to notify.');
            Log::info('Sending FCM to users:', $members->pluck('fcm_token')->toArray());
            if ($members && $members->count()) {
                Log::info('sending to notification...');
                $notify->sendFcm(
                    $members,
                    'Pengingat Rapat',
                    "Rapat \"{$meeting->name}\" akan dilaksanakan besok",
                    [
                        'meeting_id' => $meeting->id,
                        'datetime' => $meeting->datetime,
                        'type' => 'meeting_reminder',
                    ]
                );
            }

            $meeting->reminder_sent_at = now();
            $meeting->save();
        }
        Log::info('SendMeetingReminderJob succeed.');
    }
}
