<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SavingsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $users = DB::table('users')->where('role', 'group_member')->whereNotNull('group_id')->get();

        $rows = [];

        foreach ($users as $user) {
            $rows[] = [
                'saving_type' => 'shared_liability',
                'group_id' => $user->group_id,
                'user_id' => $user->id,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $rows[] = [
                'saving_type' => 'group',
                'group_id' => $user->group_id,
                'user_id' => $user->id,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($rows)) {
            DB::table('savings')->insert($rows);
        }
    }
}


