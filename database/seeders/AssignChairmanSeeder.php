<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignChairmanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
    {
        $now = Carbon::now();

        $groups = DB::table('groups')
            ->whereNull('chairman_id')
            ->get();

        if ($groups->isEmpty()) {
            $this->command->info('Semua grup sudah memiliki chairman.');
            return;
        }

        foreach ($groups as $group) {

            $member = DB::table('users')
                ->where('role', 'group_member')
                ->where('group_id', $group->id)
                ->inRandomOrder()
                ->first();

            if (!$member) {
                $this->command->warn(
                    "Grup ID {$group->id} tidak memiliki member, dilewati."
                );
                continue;
            }

            DB::table('groups')
                ->where('id', $group->id)
                ->update([
                    'chairman_id' => $member->id,
                    'updated_at' => $now,
                ]);
        }

    }

}
