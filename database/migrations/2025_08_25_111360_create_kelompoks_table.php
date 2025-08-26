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
        Schema::create('kelompoks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nomor');
            $table->longText('ketetapan');
            $table->integer('besaran_kas_tanggung_renteng');
            $table->integer('besaran_kas_kelompok');
            $table->integer('besaran_kas_dana_sosial');
            $table->bigInteger('jumlah_kas_tanggung_renteng')->default(0);
            $table->bigInteger('jumlah_kas_kelompok')->default(0);
            $table->bigInteger('jumlah_kas_dana_sosial')->default(0);
            $table->boolean('status_aktif')->default(true);

            $table->uuid('id_wilayah_kerja');
            $table->foreign('id_wilayah_kerja')->references('id')->on('wilayah_kerjas')->cascadeOnDelete();

            $table->uuid('id_pjk')->nullable();
            $table->foreign('id_pjk')->references('id')->on('users');
            $table->uuid('id_sekretaris')->nullable();
            $table->foreign('id_sekretaris')->references('id')->on('users');
            $table->uuid('id_bendahara')->nullable();
            $table->foreign('id_bendahara')->references('id')->on('users');
            $table->uuid('id_ppk')->nullable();
            $table->foreign('id_ppk')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompoks');
    }
};
