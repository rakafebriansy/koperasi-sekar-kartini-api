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
        Schema::create('pembagian_shus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('shu_jasa_usaha');
            $table->integer('shu_jasa_simpanan');

            $table->uuid('id_shu')->nullable();
            $table->foreign('id_shu')->references('id')->on('shus');
            $table->uuid('id_anggota')->nullable();
            $table->foreign('id_anggota')->references('id')->on('anggota_kelompoks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembagian_shus');
    }
};
