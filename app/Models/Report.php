<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'quarter',
        'year',
        'member_growth_in',
        'member_growth_in_percentage',
        'member_growth_out',
        'member_growth_out_percentage',
        'group_member_total',
        'group_member_total_percentage',
        'administrative_compliance_percentage',
        'deposit_compliance_percentage',
        'attendance_percentage',
        'organization_final_score_percentage',
        'loan_participation_pb',
        'loan_participation_bbm',
        'loan_participation_store',
        'cash_participation',
        'cash_participation_percentage',
        'savings_participation',
        'savings_participation_percentage',
        'meeting_deposit_percentage',
        'loan_balance_pb',
        'loan_balance_bbm',
        'loan_balance_store',
        'receivable_score',
        'financial_final_score_percentage',
        'combined_final_score_percentage',
        'criteria',
        'group_id',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
