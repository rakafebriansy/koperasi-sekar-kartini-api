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
        Schema::create('riwayat_setoran_simpanan_anggotas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('jenis', [
                'simpanan_pokok',
                'simpanan_wajib',
                'simpanan_wajib_khusus',
                'simpanan_sukarela',
                'simpanan_tanggung_renteng',
                'simpanan_berjangka_smile',
                'simpanan_hari_raya',
                'simpanan_hari_tua',
                'simpanan_rekreasi',
            ]);
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
        Schema::dropIfExists('riwayat_setoran_simpanan_anggotas');
    }
};
