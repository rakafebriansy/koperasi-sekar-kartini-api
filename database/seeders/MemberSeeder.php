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
            'Suwarni Suwito',
            'Sri Budhiyanti',
            'Tri Prasetyaningrum',
            'Natalia Kristanti',
            'Lini Araswati',
            'Vita Prastiningtyas',
            'Ratih Ekasari',
            'Kristiana Dyah Wardhani',
            'Tina Rosida',
            'Shela Roring',
            'Puji Rahayu',
            'Rahayu Fitria Ningsih',
            'Sri Fatmawati',
            'Misnati',
            'Aurine Meyrinda Roring',
            'Amma',
            'Agus Tiniawati',
            'Tjitjik Sulistiyorini',
            'Emy Yuliati',
            'Nunik Diah Ekawaty',
            'Yuni Sulistyowati',
            'Agus Sri Wahyuni',
            'Lilis Wahyuni',
            'Wahyu Inayati',
            'Eni Budi Handayani',
            'Siti Muayadah',
            'Siti Aisyah',
            'Niluh Putu ',
            'Yuniwati',
            'Qoniatul Khoiriyah',
            'Rusmini',
            'Riska Surya',
            'Yulianti Idayana',
            'Siti Ratnawati',
            'Melati Indah',
            'Yunda',
            'Irandra Satri',
            'Sri Kustini',
            'Safura',
            'Nofi Wijaya',
            'Aan Indirasari',
            'Veibe hesty',
            'Shafa Taliah',
            'Syifa thallahh',
            'Rhesty panca',
            'chamidah masriyah',
            'Dyah Mareta',
            'Dewi',
        ];

        $users = [
            [
                'name' => 'Ariyati Karinda W',
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
