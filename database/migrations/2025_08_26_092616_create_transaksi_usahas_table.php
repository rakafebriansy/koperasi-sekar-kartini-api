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
        Schema::create('transaksi_usahas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('jenis_transaksi', ['tunai', 'kredit']);
            $table->integer('jumlah');

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
        Schema::dropIfExists('transaksi_usahas');
    }
};
