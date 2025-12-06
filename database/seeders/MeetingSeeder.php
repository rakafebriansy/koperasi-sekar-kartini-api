<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Meeting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeetingSeeder extends Seeder
{
    public function run(): void
    {
        $groups = Group::all();

        if ($groups->count() == 0) {
            $this->command->warn("⚠️ Tidak ada groups di database. Seeder Meeting dilewati.");
            return;
        }

        $data = [
            [
                'meeting_type' => 'Activity',
                'date' => '2025-01-15',
                'time' => '13:00',
                'location' => 'Balai Desa',
                'photo' => 'meeting1.jpg',
                'description' => 'Pelatihan peningkatan kapasitas anggota kelompok.',
            ],
            [
                'meeting_type' => 'Routine Meeting',
                'date' => '2025-01-20',
                'time' => '09:00',
                'location' => 'Rumah Ketua',
                'photo' => 'meeting2.jpg',
                'description' => 'Pertemuan rutin bulanan untuk evaluasi kegiatan.',
            ],
            [
                'meeting_type' => 'Activity',
                'date' => '2025-02-05',
                'time' => '10:00',
                'location' => 'Aula Kecamatan',
                'photo' => 'meeting3.jpg',
                'description' => 'Kegiatan workshop mengenai pengelolaan dana kelompok.',
            ],
        ];

        foreach ($data as $meeting) {
            Meeting::create([
                ...$meeting,
                'group_id' => $groups->random()->id,
            ]);
        }
    }
}


