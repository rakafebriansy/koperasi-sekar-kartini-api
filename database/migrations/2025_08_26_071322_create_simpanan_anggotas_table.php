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
        Schema::create('simpanan_anggotas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->bigInteger('jumlah_simpanan_pokok')->default(0);
            $table->bigInteger('jumlah_simpanan_wajib')->default(0);
            $table->bigInteger('jumlah_simpanan_wajib_khusus')->default(0);
            $table->bigInteger('jumlah_simpanan_sukarela')->default(0);
            $table->bigInteger('jumlah_simpanan_tanggung_renteng')->default(0);
            $table->bigInteger('jumlah_simpanan_berjangka_smile')->default(0);
            $table->bigInteger('jumlah_simpanan_hari_raya')->default(0);
            $table->bigInteger('jumlah_simpanan_hari_tua')->default(0);
            $table->bigInteger('jumlah_simpanan_rekreasi')->default(0);

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
        Schema::dropIfExists('simpanan_anggotas');
    }
};
