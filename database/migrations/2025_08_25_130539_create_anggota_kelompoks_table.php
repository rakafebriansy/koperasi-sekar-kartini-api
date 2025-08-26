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
        Schema::create('anggota_kelompoks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nomor_anggota');
            $table->boolean('status_aktif')->default(true);
            $table->string('file_kartu_tanda_anggota')->nullable();
            $table->longText('catatan');
            
            $table->uuid('id_user');
            $table->foreign('id_user')->references('id')->on('user')->cascadeOnDelete();
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
        Schema::dropIfExists('anggota_kelompoks');
    }
};
