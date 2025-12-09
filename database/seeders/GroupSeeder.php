<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;
use App\Models\WorkArea;
use App\Models\User;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workAreas = WorkArea::all();
        $users = User::all();

        if ($workAreas->count() == 0) {
            $this->command->warn("⚠️ Tidak ada work_areas di database. Seeder Group dilewati.");
            return;
        }

        $groups = [
            [
                'number' => 1,
                'description' => 'Kelompok Mawar adalah kelompok yang berfokus pada pengembangan usaha kecil menengah.',
                'shared_liability_fund_amount' => 50000,
                'group_fund_amount' => 75000,
                'social_fund_amount' => 25000,
                'is_active' => true,
            ],
            [
                'number' => 2,
                'description' => 'Kelompok Melati aktif dalam kegiatan pemberdayaan masyarakat.',
                'shared_liability_fund_amount' => 30000,
                'group_fund_amount' => 45000,
                'social_fund_amount' => 15000,
                'is_active' => true,
            ],
            [
                'number' => 3,
                'description' => 'Kelompok Anggrek berfokus pada pertanian organik.',
                'shared_liability_fund_amount' => 40000,
                'group_fund_amount' => 60000,
                'social_fund_amount' => 20000,
                'is_active' => false,
            ],
        ];

        foreach ($groups as $group) {
            Group::create([
                ...$group,

                'work_area_id' => $workAreas->random()->id,

                'chairman_id' => $users->count() ? $users->random()->id : null,
                'facilitator_id' => $users->count() ? $users->random()->id : null,
            ]);
        }
    }
}
