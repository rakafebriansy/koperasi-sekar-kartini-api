<?php

namespace App\Jobs;

use App\Models\Meeting;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        $meetings = Meeting::whereNull('reminder_sent_at')
            ->whereBetween('datetime', [
                now()->addHours(24)->subMinutes(1),
                now()->addHours(24)->addMinutes(1),
            ])
            ->get();

        foreach ($meetings as $meeting) {

            $members = $meeting->type === 'group'
                ? $meeting->group?->members
                : \App\Models\User::all();

            if ($members && $members->count()) {
                $notify->sendFcm(
                    $members,
                    'Pengingat Rapat',
                    "Rapat \"{$meeting->name}\" akan dilaksanakan besok",
                    [
                        'meeting_id' => $meeting->id,
                        'datetime' => $meeting->datetime->toIso8601String(),
                        'type' => 'meeting_reminder',
                    ]
                );
            }

            $meeting->update([
                'reminder_sent_at' => now(),
            ]);
        }
    }
}
