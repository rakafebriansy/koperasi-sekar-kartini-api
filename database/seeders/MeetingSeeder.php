<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeetingSeeder extends Seeder
{
public function run(): void
    {
        $now = Carbon::now();

        $groupIds = DB::table('groups')->pluck('id')->toArray();
        $userIds  = DB::table('users')->pluck('id')->toArray();

        if (empty($groupIds) || empty($userIds)) {
            $this->command->warn('Seeder dibatalkan: groups atau users kosong');
            return;
        }

        $meetings = [];

        for ($i = 1; $i <= 5; $i++) {
            $meetings[] = [
                'name' => 'Rapat Kelompok Bulanan #' . $i,
                'type' => 'group',
                'datetime' => $now->copy()->subDays(rand(2, 10)),
                'location' => 'Balai Desa Setempat',
                'photo' => null,
                'description' => 'Rapat evaluasi kegiatan kelompok yang telah dilaksanakan.',
                'group_id' => $groupIds[array_rand($groupIds)],
                'user_id' => $userIds[array_rand($userIds)],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        for ($i = 6; $i <= 10; $i++) {
            $meetings[] = [
                'name' => 'Rapat Koordinasi Terbaru #' . $i,
                'type' => 'coop',
                'datetime' => $now->copy()->subHours(rand(1, 23)),
                'location' => 'Kantor Koperasi',
                'photo' => null,
                'description' => 'Rapat koordinasi koperasi dalam 24 jam terakhir.',
                'group_id' => $groupIds[array_rand($groupIds)],
                'user_id' => $userIds[array_rand($userIds)],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('meetings')->insert($meetings);
    }
}


