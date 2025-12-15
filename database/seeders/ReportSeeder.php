<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $currentYear = (int) $now->year;

        $groups = DB::table('groups')->pluck('id')->toArray();

        if (empty($groups)) {
            $this->command->warn('Seeder dibatalkan: groups kosong');
            return;
        }

        $criteriaMap = function ($score) {
            if ($score >= 90) return 'sangat_baik';
            if ($score >= 80) return 'baik';
            if ($score >= 70) return 'cukup';
            if ($score >= 60) return 'kurang';
            return 'sangat_kurang';
        };

        $data = [];

        for ($year = $currentYear - 2; $year <= $currentYear; $year++) {

            for ($quarter = 1; $quarter <= 4; $quarter++) {

                foreach ($groups as $groupId) {

                    $orgScore = rand(65, 95);
                    $finScore = rand(60, 95);
                    $finalScore = (int) round(($orgScore + $finScore) / 2);

                    $data[] = [
                        'quarter' => $quarter,
                        'year' => $year,

                        'member_growth_in' => rand(0, 10),
                        'member_growth_in_percentage' => rand(0, 20),
                        'member_growth_out' => rand(0, 5),
                        'member_growth_out_percentage' => rand(0, 10),

                        'group_member_total' => rand(10, 30),
                        'group_member_total_percentage' => rand(70, 100),

                        'administrative_compliance_percentage' => rand(70, 100),
                        'deposit_compliance_percentage' => rand(65, 100),
                        'attendance_percentage' => rand(60, 100),

                        'organization_final_score_percentage' => $orgScore,

                        'loan_participation_pb' => rand(0, 20),
                        'loan_participation_bbm' => rand(0, 20),
                        'loan_participation_store' => rand(0, 20),

                        'cash_participation' => rand(5, 30),
                        'cash_participation_percentage' => rand(60, 100),

                        'savings_participation' => rand(5, 30),
                        'savings_participation_percentage' => rand(60, 100),

                        'meeting_deposit_percentage' => rand(60, 100),

                        'loan_balance_pb' => rand(5, 50),
                        'loan_balance_bbm' => rand(5, 50),
                        'loan_balance_store' => rand(5, 50),

                        'receivable_score' => rand(60, 100),

                        'financial_final_score_percentage' => $finScore,
                        'combined_final_score_percentage' => $finalScore,

                        'criteria' => $criteriaMap($finalScore),

                        'group_id' => $groupId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        DB::table('reports')->insert($data);
    }
}
