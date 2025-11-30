<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MemberGroup;
use App\Models\WorkArea;
use App\Models\User;

class MemberGroupSeeder extends Seeder
{
    public function run(): void
    {
        $workAreas = WorkArea::pluck('id')->toArray();

        // $chairmans = User::where('role', 'group_member')->pluck('id')->toArray();
        // $facilitators = User::where('role', 'employee')->pluck('id')->toArray();
        // $treasurers = User::where('role', 'group_member')->pluck('id')->toArray();

        MemberGroup::insert([
            [
                'number' => 'MG-001',
                'description' => 'Kelompok anggota pertama',
                'shared_liability_fund_amount' => 50000,
                'group_fund_amount' => 75000,
                'social_fund_amount' => 30000,
                'total_shared_liability_fund' => 1500000,
                'total_group_fund' => 2500000,
                'total_social_fund' => 1000000,
                'is_active' => true,
                'work_area_id' => $workAreas[array_rand($workAreas)] ?? null,
                // 'chairman_id' => $chairmans[array_rand($chairmans)] ?? null,
                // 'facilitator_id' => $facilitators[array_rand($facilitators)] ?? null,
                // 'treasurer_id' => $treasurers[array_rand($treasurers)] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'number' => 'MG-002',
                'description' => 'Kelompok anggota kedua',
                'shared_liability_fund_amount' => 60000,
                'group_fund_amount' => 80000,
                'social_fund_amount' => 40000,
                'total_shared_liability_fund' => 1600000,
                'total_group_fund' => 2600000,
                'total_social_fund' => 1200000,
                'is_active' => true,
                'work_area_id' => $workAreas[array_rand($workAreas)] ?? null,
                // 'chairman_id' => $chairmans[array_rand($chairmans)] ?? null,
                // 'facilitator_id' => $facilitators[array_rand($facilitators)] ?? null,
                // 'treasurer_id' => $treasurers[array_rand($treasurers)] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
