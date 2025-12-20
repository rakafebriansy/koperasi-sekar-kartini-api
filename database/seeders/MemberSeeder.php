<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $workAreaIds = DB::table('work_areas')->pluck('id')->toArray();
        $groupIds = DB::table('groups')->pluck('id')->toArray();

        if (empty($workAreaIds) || empty($groupIds)) {
            $this->command->warn('Seeder dibatalkan: work_areas atau groups kosong');
            return;
        }

        $names = [
            'Andi Saputra',
            'Sri Wahyuni',
            'Muhammad Arif',
            'Nur Aisyah',
            'Rudi Hartono',
            'Lina Marlina',
            'Agus Setiawan',
            'Fitri Handayani',
            'Hendra Wijaya',
            'Rina Susanti',
            'Doni Prakoso',
            'Yuliana Putri',
            'Ahmad Zainal',
            'Siti Aminah',
            'Bayu Kurniawan',
            'Diah Puspitasari',
            'Fajar Nugroho',
            'Rizka Amelia',
            'Eko Prasetyo',
            'Novi Lestari',
            'Taufik Hidayat',
            'Intan Permata',
            'Arman Hakim',
            'Wulan Sari',
            'Ilham Maulana',
            'Putri Ayu',
            'Robby Firmansyah',
            'Mega Safitri',
            'Arief Rahman',
            'Desi Anggraini',
        ];

        $users = [
            [
                'name' => 'Rangkasbitung',
                'member_number' => 'MBR-999',
                'identity_number' => '3201113311110001',
                'birth_date' => '1983-03-12',
                'phone_number' => '0812345778',
                'address' => 'Jl. Anggur No. 10, Sumbersari, Jember',
                'occupation' => 'Atmin',
                'identity_card_photo' => null,
                'self_photo' => null,
                'member_card_photo' => null,
                'email_verified_at' => $now,
                'password' => Hash::make('password'),
                'role' => 'group_member',
                'is_active' => true,
                'work_area_id' => $workAreaIds[array_rand($workAreaIds)],
                'group_id' => $groupIds[array_rand($groupIds)],
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ];

        foreach ($names as $i => $name) {
            $users[] = [
                'name' => $name,
                'member_number' => 'MBR-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'identity_number' => '350901' . str_pad($i + 1, 10, '0', STR_PAD_LEFT),
                'birth_date' => Carbon::create(1985, 1, 1)->addDays($i * 120),
                'phone_number' => '0821' . str_pad($i + 1, 8, '0', STR_PAD_LEFT),
                'address' => 'Kabupaten Jember, Jawa Timur',
                'occupation' => 'Wiraswasta',
                'identity_card_photo' => null,
                'self_photo' => null,
                'member_card_photo' => null,
                'email_verified_at' => $now,
                'password' => Hash::make('password'),
                'role' => 'group_member',
                'is_active' => true,
                'work_area_id' => $workAreaIds[array_rand($workAreaIds)],
                'group_id' => $groupIds[array_rand($groupIds)],
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('users')->insert($users);
    }
}
