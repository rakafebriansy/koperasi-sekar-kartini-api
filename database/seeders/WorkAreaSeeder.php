<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::table('work_areas')->insert([
            [
                'name_work_area' => 'Kecamatan Sumbersari',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name_work_area' => 'Kecamatan Patrang',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name_work_area' => 'Kecamatan Kaliwates',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name_work_area' => 'Kecamatan Ambulu',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}


