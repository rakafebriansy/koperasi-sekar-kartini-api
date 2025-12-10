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
        Schema::create('savings', function (Blueprint $table) {
            $table->id()->primary();
            $table->enum('type', [
                'simpanan_pokok',
                'simpanan_wajib',
                'simpanan_wajib_khusus',
                'simpanan_sukarela',
                'simpanan_bersama',
                'simpanan_berjangka',
                'simpanan_hari_raya',
                'simpanan_hari_tua',
                'simpanan_rekreasi',
            ]);
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
        Schema::dropIfExists('savings');
    }
};


