<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
    {
        $now = Carbon::now();

        $kecamatanJember = [
            'Ajung',
            'Ambulu',
            'Arjasa',
            'Balung',
            'Bangsalsari',
            'Gumukmas',
            'Jelbuk',
            'Jenggawah',
            'Jombang',
            'Kalisat',
            'Kaliwates',
            'Kencong',
            'Ledokombo',
            'Mayang',
            'Mumbulsari',
            'Pakusari',
            'Panti',
            'Patrang',
            'Rambipuji',
            'Semboro',
            'Silo',
            'Sukorambi',
            'Sumberbaru',
            'Sumberjambe',
            'Sumbersari',
            'Tanggul',
            'Tempurejo',
            'Umbulsari',
            'Wuluhan',
        ];

        $data = array_map(function ($item) use ($now) {
            return [
                'name' => $item,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $kecamatanJember);

        DB::table('work_areas')->insert($data);
    }

}


