<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('riwayat_pengajuan_pinjaman_anggotas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('jenis',[
                'pinjaman_biasa',
                'pinjaman_pengadaan_barang',
                'pinjaman_bbm',
                'pinjaman_bahan_pokok',
                'pinjaman_barang_dagangan',
                'pinjaman_lebaran',
                'pinjaman_rekreasi',
                'pinjaman_khusus',
                'pinjaman_jadwal_ulang',
            ]);
            $table->boolean('telah_disetujui')->default(false);

            $table->uuid('id_anggota');
            $table->foreign('id_anggota')->references('id')->on('anggota_kelompoks')->cascadeOnDelete();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pengajuan_pinjaman_anggotas');
    }
};
