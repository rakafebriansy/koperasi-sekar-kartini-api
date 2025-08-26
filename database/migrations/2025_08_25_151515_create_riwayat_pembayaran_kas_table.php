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
        Schema::create('riwayat_pembayaran_kas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('jenis',['kas_tanggung_renteng','kas_kelompok','kas_dana_sosial']);
            $table->text('keterangan');

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
        Schema::dropIfExists('riwayat_pembayaran_kas');
    }
};
