<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'quarter' => (int) $this->quarter,
            'year' => (int) $this->year,
            'member_growth_in' => (int) $this->member_growth_in,
            'member_growth_in_percentage' => (int) $this->member_growth_in_percentage,
            'member_growth_out' => (int) $this->member_growth_out,
            'member_growth_out_percentage' => (int) $this->member_growth_out_percentage,
            'group_member_total' => (int) $this->group_member_total,
            'group_member_total_percentage' => (int) $this->group_member_total_percentage,
            'administrative_compliance_percentage' => (int) $this->administrative_compliance_percentage,
            'deposit_compliance_percentage' => (int) $this->deposit_compliance_percentage,
            'attendance_percentage' => (int) $this->attendance_percentage,
            'organization_final_score_percentage' => (int) $this->organization_final_score_percentage,
            'loan_participation_pb' => (int) $this->loan_participation_pb,
            'loan_participation_bbm' => (int) $this->loan_participation_bbm,
            'loan_participation_store' => (int) $this->loan_participation_store,
            'cash_participation' => (int) $this->cash_participation,
            'cash_participation_percentage' => (int) $this->cash_participation_percentage,
            'savings_participation' => (int) $this->savings_participation,
            'savings_participation_percentage' => (int) $this->savings_participation_percentage,
            'meeting_deposit_percentage' => (int) $this->meeting_deposit_percentage,
            'loan_balance_pb' => (int) $this->loan_balance_pb,
            'loan_balance_bbm' => (int) $this->loan_balance_bbm,
            'loan_balance_store' => (int) $this->loan_balance_store,
            'receivable_score' => (int) $this->receivable_score,
            'financial_final_score_percentage' => (int) $this->financial_final_score_percentage,
            'combined_final_score_percentage' => (int) $this->combined_final_score_percentage,
            'criteria' => $this->criteria,
            'group_id' => (int) $this->group_id,
        ];
    }
}
