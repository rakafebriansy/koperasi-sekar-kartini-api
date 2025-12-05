<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeetingSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $groups = DB::table('groups')->pluck('id', 'number')->toArray();

        DB::table('meetings')->insert([
            [
                'meeting_type' => 'Activity',
                'date' => '2025-12-05',
                'time' => '09:00:00',
                'location' => 'Balai Desa Sumbersari',
                'photo' => 'uploads/meetings/kegiatan_pelatihan_usaha.jpg',
                'description' => 'Pelatihan pengelolaan keuangan usaha mikro untuk anggota Kelompok Bunga Mawar.',
                'group_id' => $groups['GRP-001'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'meeting_type' => 'Routine Meeting',
                'date' => '2025-12-10',
                'time' => '15:00:00',
                'location' => 'Rumah Ketua Kelompok Bunga Mawar',
                'photo' => 'uploads/meetings/pertemuan_rutin_mawar.jpg',
                'description' => 'Pertemuan rutin bulanan untuk setoran simpanan dan pembayaran angsuran.',
                'group_id' => $groups['GRP-001'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'meeting_type' => 'Activity',
                'date' => '2025-12-06',
                'time' => '10:00:00',
                'location' => 'Balai RW Patrang',
                'photo' => 'uploads/meetings/sosialisasi_program.jpg',
                'description' => 'Sosialisasi program simpan pinjam dan penguatan modal kelompok Melati Putih.',
                'group_id' => $groups['GRP-002'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'meeting_type' => 'Routine Meeting',
                'date' => '2025-12-11',
                'time' => '14:00:00',
                'location' => 'Posko Kelompok Melati Putih',
                'photo' => 'uploads/meetings/pertemuan_rutin_melati.jpg',
                'description' => 'Pertemuan rutin bulanan untuk evaluasi usaha anggota dan pencatatan simpanan.',
                'group_id' => $groups['GRP-002'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'meeting_type' => 'Activity',
                'date' => '2025-12-07',
                'time' => '13:00:00',
                'location' => 'Kantor Koperasi Kaliwates',
                'photo' => 'uploads/meetings/pelatihan_pembukuan.jpg',
                'description' => 'Pelatihan pembukuan sederhana bagi anggota Kelompok Anggrek Ungu.',
                'group_id' => $groups['GRP-003'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'meeting_type' => 'Routine Meeting',
                'date' => '2025-12-12',
                'time' => '16:00:00',
                'location' => 'Rumah Anggota Kelompok Anggrek Ungu',
                'photo' => 'uploads/meetings/pertemuan_rutin_anggrek.jpg',
                'description' => 'Pertemuan rutin untuk pencairan pinjaman dan setoran simpanan wajib.',
                'group_id' => $groups['GRP-003'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'meeting_type' => 'Activity',
                'date' => '2025-12-08',
                'time' => '09:30:00',
                'location' => 'Balai Desa Ambulu',
                'photo' => 'uploads/meetings/penyuluhan_pendidikan.jpg',
                'description' => 'Penyuluhan pentingnya tabungan pendidikan bagi keluarga anggota Kelompok Seruni Hijau.',
                'group_id' => $groups['GRP-004'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'meeting_type' => 'Routine Meeting',
                'date' => '2025-12-13',
                'time' => '15:30:00',
                'location' => 'Sekretariat Kelompok Seruni Hijau',
                'photo' => 'uploads/meetings/pertemuan_rutin_seruni.jpg',
                'description' => 'Pertemuan rutin bulanan untuk pencatatan simpanan dan pembahasan rencana kegiatan.',
                'group_id' => $groups['GRP-004'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}


