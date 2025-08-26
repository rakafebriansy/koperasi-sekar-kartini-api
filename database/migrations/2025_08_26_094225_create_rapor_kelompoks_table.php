<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rapor_kelompoks', function (Blueprint $table) {
            $table->uuid('id');
            $table->integer('perkembangan_anggota_masuk');
            $table->integer('perkembangan_anggota_masuk_%');
            $table->integer('perkembangan_anggota_keluar');
            $table->integer('perkembangan_anggota_keluar_%');
            $table->integer('anggota_kelompok_jumlah');
            $table->integer('anggota_kelompok_jumlah_%');
            $table->integer('tertib_administrasi_%');
            $table->integer('tertib_setor_%');
            $table->integer('kehadiran_%');
            $table->integer('nilai_akhir_organisasi_%');
            $table->integer('partisipasi_pinjaman_pb');
            $table->integer('partisipasi_pinjaman_bbm');
            $table->integer('partisipasi_pinjaman_toko');
            $table->integer('partisipasi_tunai');
            $table->integer('partisipasi_tunai_%');
            $table->integer('partisipasi_simpanan');
            $table->integer('partisipasi_simpanan_%');
            $table->integer('setor_di_pertemuan_%');
            $table->integer('saldo_pinjaman_pb');
            $table->integer('saldo_pinjaman_bbm');
            $table->integer('saldo_pinjaman_toko');
            $table->integer('nilai_akhir_keuangan_%');
            $table->integer('nilai_gabungan_%');
            $table->integer('kriteria');

            $table->uuid('id_kelompok')->nullable();
            $table->foreign('id_kelompok')->references('id')->on('kelompoks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapor_kelompoks');
    }
};
