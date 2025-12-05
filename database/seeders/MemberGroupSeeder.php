<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemberGroupSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $workAreas = DB::table('work_areas')->pluck('id', 'name_work_area')->toArray();

        DB::table('groups')->insert([
            [
                'name' => 'Kelompok Bunga Mawar',
                'number' => 'GRP-001',
                'description' => 'Kelompok anggota di wilayah Sumbersari dengan fokus simpan pinjam produktif.',
                'shared_liability_fund_amount' => 50000,
                'group_fund_amount' => 75000,
                'social_fund_amount' => 25000,
                'total_shared_liability_fund' => 2000000,
                'total_group_fund' => 3000000,
                'total_social_fund' => 1000000,
                'is_active' => true,
                'work_area_id' => $workAreas['Kecamatan Sumbersari'] ?? null,
                'chairman_id' => null,
                'facilitator_id' => null,
                'secretary_id' => null,
                'treasurer_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Kelompok Melati Putih',
                'number' => 'GRP-002',
                'description' => 'Kelompok anggota di wilayah Patrang dengan kegiatan simpanan rutin.',
                'shared_liability_fund_amount' => 60000,
                'group_fund_amount' => 80000,
                'social_fund_amount' => 30000,
                'total_shared_liability_fund' => 2200000,
                'total_group_fund' => 3200000,
                'total_social_fund' => 1200000,
                'is_active' => true,
                'work_area_id' => $workAreas['Kecamatan Patrang'] ?? null,
                'chairman_id' => null,
                'facilitator_id' => null,
                'secretary_id' => null,
                'treasurer_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Kelompok Anggrek Ungu',
                'number' => 'GRP-003',
                'description' => 'Kelompok anggota di wilayah Kaliwates dengan fokus pembiayaan usaha kecil.',
                'shared_liability_fund_amount' => 55000,
                'group_fund_amount' => 70000,
                'social_fund_amount' => 25000,
                'total_shared_liability_fund' => 2100000,
                'total_group_fund' => 2800000,
                'total_social_fund' => 900000,
                'is_active' => true,
                'work_area_id' => $workAreas['Kecamatan Kaliwates'] ?? null,
                'chairman_id' => null,
                'facilitator_id' => null,
                'secretary_id' => null,
                'treasurer_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Kelompok Seruni Hijau',
                'number' => 'GRP-004',
                'description' => 'Kelompok anggota di wilayah Ambulu dengan fokus tabungan pendidikan.',
                'shared_liability_fund_amount' => 50000,
                'group_fund_amount' => 65000,
                'social_fund_amount' => 20000,
                'total_shared_liability_fund' => 1800000,
                'total_group_fund' => 2600000,
                'total_social_fund' => 800000,
                'is_active' => true,
                'work_area_id' => $workAreas['Kecamatan Ambulu'] ?? null,
                'chairman_id' => null,
                'facilitator_id' => null,
                'secretary_id' => null,
                'treasurer_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}


