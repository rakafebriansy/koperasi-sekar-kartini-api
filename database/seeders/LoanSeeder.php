<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $userIds = DB::table('users')->pluck('id')->toArray();

        if (empty($userIds)) {
            $this->command->warn('Seeder dibatalkan: users kosong');
            return;
        }

        $types = [
            'pinjaman_biasa',
            'pinjaman_pengadaan_barang',
            'pinjaman_bbm',
            'pinjaman_bahan_pokok',
            'pinjaman_barang_dagangan',
            'pinjaman_lebaran',
            'pinjaman_rekreasi',
            'pinjaman_spesial',
        ];

        $statuses = ['unpaid', 'paid'];

        $data = [];

        for ($i = 1; $i <= 100; $i++) {
            $year  = rand(2022, (int) $now->year);
            $month = rand(1, 12);

            $data[] = [
                'type' => $types[array_rand($types)],
                'status' => $statuses[array_rand($statuses)],
                'nominal' => rand(50, 500) * 10000, // 500rb – 5jt
                'year' => $year,
                'month' => $month,
                'user_id' => $userIds[array_rand($userIds)],
                'created_at' => Carbon::create($year, $month, rand(1, 28)),
                'updated_at' => $now,
            ];
        }

        DB::table('loans')->insert($data);
    }
}
