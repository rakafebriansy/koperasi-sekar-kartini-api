<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoansSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $users = DB::table('users')
            ->where('role', 'group_member')
            ->whereNotNull('group_id')
            ->orderBy('id')
            ->limit(8)
            ->get();

        $rows = [];

        foreach ($users as $index => $user) {
            $rows[] = [
                'loan_type' => $index % 2 === 0 ? 'type_a' : 'type_b',
                'status' => $index % 3 === 0 ? 'approved' : 'in_process',
                'user_id' => $user->id,
                'group_id' => $user->group_id,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($rows)) {
            DB::table('loans')->insert($rows);
        }
    }
}


