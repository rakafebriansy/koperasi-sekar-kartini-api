<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersMeetingsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $users = DB::table('users')->where('role', 'group_member')->whereNotNull('group_id')->get();

        $groupMembers = [];
        foreach ($users as $user) {
            $groupMembers[$user->group_id][] = $user->id;
        }

        $meetings = DB::table('meetings')->orderBy('id')->get();

        $rows = [];

        foreach ($meetings as $meeting) {
            $participants = $groupMembers[$meeting->group_id] ?? [];
            $participants = array_slice($participants, 0, 3);

            foreach ($participants as $userId) {
                $rows[] = [
                    'user_id' => $userId,
                    'meeting_id' => $meeting->id,
                    'status' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if (!empty($rows)) {
            DB::table('users_meetings')->insert($rows);
        }
    }
}


