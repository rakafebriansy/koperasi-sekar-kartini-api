<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SavingsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $userIds = DB::table('users')->pluck('id')->toArray();

        if (empty($userIds)) {
            $this->command->warn('Seeder dibatalkan: users kosong');
            return;
        }

        $types = [
            'simpanan_pokok',
            'simpanan_wajib',
            'simpanan_wajib_khusus',
            'simpanan_sukarela',
            'simpanan_bersama',
            'simpanan_berjangka',
            'simpanan_hari_raya',
            'simpanan_hari_tua',
            'simpanan_rekreasi',
        ];

        $data = [];

        for ($i = 1; $i <= 100; $i++) {
            $year = rand(2022, (int) $now->year);
            $month = rand(1, 12);

            $data[] = [
                'type' => $types[array_rand($types)],
                'nominal' => rand(10, 100) * 10000, // 100rb – 1jt
                'year' => $year,
                'month' => $month,
                'user_id' => $userIds[array_rand($userIds)],
                'created_at' => Carbon::create($year, $month, rand(1, 28)),
                'updated_at' => $now,
            ];
        }

        DB::table('savings')->insert($data);
    }
}


