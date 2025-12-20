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
        Schema::create('reports', function (Blueprint $table) {
            $table->id('id');
            $table->tinyInteger('quarter');
            $table->smallInteger('year');
            $table->integer('member_growth_in');
            $table->float('member_growth_in_percentage');
            $table->integer('member_growth_out');
            $table->float('member_growth_out_percentage');
            $table->integer('group_member_total');
            $table->float('group_member_total_percentage');
            $table->float('administrative_compliance_percentage');
            $table->float('deposit_compliance_percentage');
            $table->float('attendance_percentage');
            $table->float('organization_final_score_percentage');
            $table->integer('loan_participation_pb');
            $table->integer('loan_participation_bbm');
            $table->integer('loan_participation_store');
            $table->integer('cash_participation');
            $table->float('cash_participation_percentage');
            $table->integer('savings_participation');
            $table->float('savings_participation_percentage');
            $table->float('meeting_deposit_percentage');
            $table->integer('loan_balance_pb');
            $table->integer('loan_balance_bbm');
            $table->integer('loan_balance_store');
            $table->float('receivable_score');
            $table->float('financial_final_score_percentage');
            $table->float('combined_final_score_percentage');
            $table->enum('criteria', [
                'sangat_baik',
                'baik',
                'cukup',
                'kurang',
                'sangat_kurang'
            ]);

            $table->unsignedBigInteger('group_id');
            $table->foreign('group_id')->references('id')->on('groups')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
