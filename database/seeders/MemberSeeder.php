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

        $workAreas = DB::table('work_areas')->pluck('id', 'name')->toArray();

        $members = [
            [
                'group_number' => 1,
                'member_number' => 'MBR-001',
                'name' => 'Ahmad Setiawan',
                'identity_number' => '3509101205910001',
                'birth_date' => '1991-05-12',
                'phone_number' => '081234560001',
                'address' => 'Dusun Krajan 1, Desa Sumberjati, Kab. Jember',
                'occupation' => 'Pedagang Sembako',
                'work_area_key' => 'Kecamatan Sumbersari',
            ],
            [
                'group_number' => 1,
                'member_number' => 'MBR-002',
                'name' => 'Nur Aisyah',
                'identity_number' => '3509100303920002',
                'birth_date' => '1992-03-03',
                'phone_number' => '081234560002',
                'address' => 'Dusun Karangrejo, Desa Sumberjati, Kab. Jember',
                'occupation' => 'Ibu Rumah Tangga',
                'work_area_key' => 'Kecamatan Sumbersari',
            ],
            [
                'group_number' => 1,
                'member_number' => 'MBR-003',
                'name' => 'Ridwan Pratama',
                'identity_number' => '3509102209890003',
                'birth_date' => '1989-09-22',
                'phone_number' => '081234560003',
                'address' => 'Dusun Krajan 2, Desa Sumberjati, Kab. Jember',
                'occupation' => 'Teknisi Elektronik',
                'work_area_key' => 'Kecamatan Sumbersari',
            ],
            [
                'group_number' => 1,
                'member_number' => 'MBR-004',
                'name' => 'Lina Marlina',
                'identity_number' => '3509101707940004',
                'birth_date' => '1994-07-17',
                'phone_number' => '081234560004',
                'address' => 'Dusun Gumuk, Desa Sumberjati, Kab. Jember',
                'occupation' => 'Penjahit',
                'work_area_key' => 'Kecamatan Sumbersari',
            ],
            [
                'group_number' => 1,
                'member_number' => 'MBR-005',
                'name' => 'Fajar Hidayat',
                'identity_number' => '3509100111900005',
                'birth_date' => '1990-11-01',
                'phone_number' => '081234560005',
                'address' => 'Dusun Krajan 3, Desa Sumberjati, Kab. Jember',
                'occupation' => 'Petani Kopi',
                'work_area_key' => 'Kecamatan Sumbersari',
            ],
            [
                'group_number' => 2,
                'member_number' => 'MBR-006',
                'name' => 'Sari Puspita',
                'identity_number' => '3509101102930006',
                'birth_date' => '1993-02-11',
                'phone_number' => '081234560006',
                'address' => 'Dusun Kaliwates, Desa Wonoasri, Kab. Jember',
                'occupation' => 'Penjual Kue',
                'work_area_key' => 'Kecamatan Sumbersari',
            ],
            [
                'group_number' => 2,
                'member_number' => 'MBR-007',
                'name' => 'Yusuf Ramadhan',
                'identity_number' => '3509102004880007',
                'birth_date' => '1988-04-20',
                'phone_number' => '081234560007',
                'address' => 'Dusun Karanganyar, Desa Wonoasri, Kab. Jember',
                'occupation' => 'Supir Angkot',
                'work_area_key' => 'Kecamatan Sumbersari',
            ],
            [
                'group_number' => 2,
                'member_number' => 'MBR-008',
                'name' => 'Mega Sari',
                'identity_number' => '3509101506950008',
                'birth_date' => '1995-06-15',
                'phone_number' => '081234560008',
                'address' => 'Dusun Krajan, Desa Wonoasri, Kab. Jember',
                'occupation' => 'Pedagang Pakaian',
                'work_area_key' => 'Kecamatan Sumbersari',
            ],
            [
                'group_number' => 2,
                'member_number' => 'MBR-009',
                'name' => 'Dodi Santoso',
                'identity_number' => '3509102908900009',
                'birth_date' => '1990-08-29',
                'phone_number' => '081234560009',
                'address' => 'Dusun Kebonsari, Desa Wonoasri, Kab. Jember',
                'occupation' => 'Montir Bengkel',
                'work_area_key' => 'Kecamatan Ambulu',
            ],
            [
                'group_number' => 2,
                'member_number' => 'MBR-010',
                'name' => 'Rina Wulandari',
                'identity_number' => '3509102501920010',
                'birth_date' => '1992-01-25',
                'phone_number' => '081234560010',
                'address' => 'Dusun Sumberjo, Desa Wonoasri, Kab. Jember',
                'occupation' => 'Pemilik Warung',
                'work_area_key' => 'Kecamatan Sumbersari',

            ],
        ];

        $rows = [];

        foreach ($members as $m) {

            $rows[] = [
                'name' => $m['name'],
                'member_number' => $m['member_number'],
                'identity_number' => $m['identity_number'],
                'birth_date' => $m['birth_date'],
                'phone_number' => $m['phone_number'],
                'address' => $m['address'],
                'occupation' => $m['occupation'],
                'identity_card_photo' => 'uploads/ktp/' . Str::slug($m['name']) . '.jpg',
                'self_photo' => 'uploads/self/' . Str::slug($m['name']) . '.jpg',
                'password' => Hash::make('password'),
                'role' => 'group_member',
                'is_active' => true,
                'group_id' => $groups[$m['group_number']] ?? null,
                'work_area_id' => $workAreas[$m['work_area_key']] ?? null,
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('users')->insert($rows);
    }
}
