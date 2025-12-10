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
        Schema::create('loans', function (Blueprint $table) {
            $table->id()->primary();
            $table->enum('type', [
                'pinjaman_biasa',
                'pinjaman_pengadaan_barang',
                'pinjaman_bbm',
                'pinjaman_bahan_pokok',
                'pinjaman_barang_dagangan',
                'pinjaman_lebaran',
                'pinjaman_rekreasi',
                'pinjaman_spesial',
            ]);
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
            $table->unsignedBigInteger('nominal');
            $table->integer('year');
            $table->tinyInteger('month');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};


