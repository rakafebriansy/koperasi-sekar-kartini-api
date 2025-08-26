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
        Schema::create('shus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->year('tahun');
            $table->bigInteger('total_pendapatan');
            $table->bigInteger('total_biaya');
            $table->bigInteger('shu_bersih');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shus');
    }
};
