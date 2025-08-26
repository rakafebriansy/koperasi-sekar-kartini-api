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
        Schema::create('riwayat_pemakaian_kas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('jenis',['kas_tanggung_renteng','kas_kelompok','kas_dana_sosial']);
            $table->bigInteger('jumlah_pemakaian');
            $table->text('tujuan_pemakaian');

            $table->uuid('id_kelompok');
            $table->foreign('id_kelompok')->references('id')->on('kelompoks')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pemakaian_kas');
    }
};
