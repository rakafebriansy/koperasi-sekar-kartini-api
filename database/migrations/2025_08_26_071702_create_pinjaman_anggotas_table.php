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
        Schema::create('pinjaman_anggotas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->bigInteger('jumlah_pinjaman_biasa')->default(0);
            $table->bigInteger('jumlah_pinjaman_pengadaan_barang')->default(0);
            $table->bigInteger('jumlah_pinjaman_bbm')->default(0);
            $table->bigInteger('jumlah_pinjaman_bahan_pokok')->default(0);
            $table->bigInteger('jumlah_pinjaman_barang_dagangan')->default(0);
            $table->bigInteger('jumlah_pinjaman_lebaran')->default(0);
            $table->bigInteger('jumlah_pinjaman_rekreasi')->default(0);
            $table->bigInteger('jumlah_pinjaman_khusus')->default(0);
            $table->bigInteger('jumlah_pinjaman_jadwal_ulang')->default(0);

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
        Schema::dropIfExists('pinjaman_anggotas');
    }
};
