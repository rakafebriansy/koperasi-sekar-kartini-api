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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('number')->unique();
            $table->longText('description');
            $table->integer('shared_liability_fund_amount')->default(0);
            $table->integer('group_fund_amount')->default(0);
            $table->integer('social_fund_amount')->default(0);
            $table->bigInteger('total_shared_liability_fund')->default(0);
            $table->bigInteger('total_group_fund')->default(0);
            $table->bigInteger('total_social_fund')->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('work_area_id')->nullable();
            $table->foreign('work_area_id')->references('id')->on('work_areas')->onDelete('set null');
            $table->unsignedBigInteger('chairman_id')->nullable();
            $table->foreign('chairman_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('facilitator_id')->nullable();
            $table->foreign('facilitator_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('secretary_id')->nullable();
            $table->foreign('secretary_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('treasurer_id')->nullable();
            $table->foreign('treasurer_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_groups');
    }
};
