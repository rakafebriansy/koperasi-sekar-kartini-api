<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $groups = DB::table('groups')->pluck('id', 'number')->toArray();

        $memberData = [
            ['name' => 'Ahmad Setiawan', 'group' => 'GRP-001'],
            ['name' => 'Nur Aisyah', 'group' => 'GRP-001'],
            ['name' => 'Ridwan Pratama', 'group' => 'GRP-001'],
            ['name' => 'Lina Marlina', 'group' => 'GRP-001'],
            ['name' => 'Fajar Hidayat', 'group' => 'GRP-001'],
            ['name' => 'Sari Puspita', 'group' => 'GRP-002'],
            ['name' => 'Yusuf Ramadhan', 'group' => 'GRP-002'],
            ['name' => 'Mega Sari', 'group' => 'GRP-002'],
            ['name' => 'Dodi Santoso', 'group' => 'GRP-002'],
            ['name' => 'Rina Wulandari', 'group' => 'GRP-002'],
            ['name' => 'Hendra Gunawan', 'group' => 'GRP-003'],
            ['name' => 'Putri Lestari', 'group' => 'GRP-003'],
            ['name' => 'Agus Salim', 'group' => 'GRP-003'],
            ['name' => 'Bella Pratiwi', 'group' => 'GRP-003'],
            ['name' => 'Iwan Kurniawan', 'group' => 'GRP-003'],
            ['name' => 'Nina Kartika', 'group' => 'GRP-004'],
            ['name' => 'Rudi Hartono', 'group' => 'GRP-004'],
            ['name' => 'Fitri Handayani', 'group' => 'GRP-004'],
            ['name' => 'Dian Saputra', 'group' => 'GRP-004'],
            ['name' => 'Wahyu Prasetyo', 'group' => 'GRP-004'],
        ];

        $identityBase = 3201222200000000;
        $phoneBase = 81230000000;

        $rows = [];

        foreach ($memberData as $index => $data) {
            $number = $index + 1;
            $name = $data['name'];
            $groupNumber = $data['group'];

            $rows[] = [
                'name' => $name,
                'member_number' => 'MBR-' . str_pad((string)$number, 3, '0', STR_PAD_LEFT),
                'identity_number' => (string)($identityBase + $number),
                'birth_date' => '1990-01-' . str_pad((string)(($number % 28) + 1), 2, '0', STR_PAD_LEFT),
                'phone_number' => '0' . (string)($phoneBase + $number),
                'address' => 'Dusun ' . ($number % 5 + 1) . ', Desa Fiktif, Jember',
                'occupation' => 'Pelaku Usaha Mikro',
                'identity_card_photo' => 'uploads/ktp/' . Str::slug($name) . '.jpg',
                'self_photo' => 'uploads/self/' . Str::slug($name) . '.jpg',
                'password' => Hash::make('password'),
                'role' => 'group_member',
                'is_verified' => true,
                'is_active' => true,
                'work_area_id' => null,
                'group_id' => $groups[$groupNumber] ?? null,
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('users')->insert($rows);
    }
}


