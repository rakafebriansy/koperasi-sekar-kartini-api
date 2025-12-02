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
        Schema::create('users_meetings', function (Blueprint $table) {
            $table->id()->primary();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('meeting_id')->nullable();
            $table->boolean('status')->default(false);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('meeting_id')->references('id')->on('meetings')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_meetings');
    }
};


