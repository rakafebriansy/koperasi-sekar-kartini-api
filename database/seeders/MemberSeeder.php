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

        $names = [
            'Ahmad Setiawan', 'Nur Aisyah', 'Ridwan Pratama', 'Lina Marlina', 'Fajar Hidayat',
            'Sari Puspita', 'Yusuf Ramadhan', 'Mega Sari', 'Dodi Santoso', 'Rina Wulandari',
            'Hendra Gunawan', 'Putri Lestari', 'Agus Salim', 'Bella Pratiwi', 'Iwan Kurniawan',
            'Nina Kartika', 'Rudi Hartono', 'Fitri Handayani', 'Dian Saputra', 'Wahyu Prasetyo',
            'Mila Zahra', 'Tono Prasetyo', 'Lukman Hakim', 'Eka Putri', 'Rama Febrianto',
            'Nia Kusuma', 'Rizki Maulana', 'Dewi Sartika', 'Slamet Riyadi', 'Amelia Putri'
        ];

        $identityBase = 3201222200000000;
        $phoneBase = 81230000000;

        $rows = [];
        $counter = 101;

        foreach ($groups as $groupNumber => $groupId) {

            for ($i = 0; $i < 5; $i++) {

                $name = $names[array_rand($names)];
                $counter++;

                $rows[] = [
                    'name' => $name,
                    'member_number' => 'MBR-' . str_pad((string)$counter, 3, '0', STR_PAD_LEFT),
                    'identity_number' => (string)($identityBase + $counter),
                    'birth_date' => '1990-01-' . str_pad((string)(($counter % 28) + 1), 2, '0', STR_PAD_LEFT),
                    'phone_number' => '0' . ($phoneBase + $counter),
                    'address' => 'Dusun ' . ($counter % 5 + 1) . ', Desa Fiktif, Jember',
                    'occupation' => 'Pelaku Usaha Mikro',
                    'identity_card_photo' => 'uploads/ktp/' . Str::slug($name) . '.jpg',
                    'self_photo' => 'uploads/self/' . Str::slug($name) . '.jpg',
                    'password' => Hash::make('password'),
                    'role' => 'group_member',
                    'is_verified' => true,
                    'is_active' => true,
                    'work_area_id' => null,
                    'group_id' => $groupId,
                    'remember_token' => Str::random(10),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('users')->insert($rows);
    }
}
