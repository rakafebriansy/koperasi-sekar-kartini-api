<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkAreaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('work_areas')->insert([
            [
                'name_work_area' => 'Kecamatan Kaliwates',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_work_area' => 'Kecamatan Sumbersari',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_work_area' => 'Kecamatan Patrang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_work_area' => 'Kecamatan Ajung',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_work_area' => 'Kecamatan Rambipuji',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
