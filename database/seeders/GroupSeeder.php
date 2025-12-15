<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Group;
use App\Models\WorkArea;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $workAreaIds = DB::table('work_areas')->pluck('id')->toArray();

        $facilitatorIds = DB::table('users')
            ->where('role', 'employee')
            ->pluck('id')
            ->toArray();

        if (empty($workAreaIds) || empty($facilitatorIds)) {
            $this->command->warn('Seeder dibatalkan: work_areas atau users(employee) kosong');
            return;
        }

        $groups = [];

        for ($i = 1; $i <= 10; $i++) {
            $groups[] = [
                'number' => $i,
                'description' => 'Kelompok usaha binaan ke-' . $i,
                'shared_liability_fund_amount' => 0,
                'group_fund_amount' => 0,
                'social_fund_amount' => 0,
                'work_area_id' => $workAreaIds[array_rand($workAreaIds)],
                'chairman_id' => null,
                'facilitator_id' => $facilitatorIds[array_rand($facilitatorIds)],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('groups')->insert($groups);
    }

}
