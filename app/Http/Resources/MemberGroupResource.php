<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'description' => $this->description,
            'shared_liability_fund_amount' => $this->shared_liability_fund_amount,
            'group_fund_amount' => $this->group_fund_amount,
            'social_fund_amount' => $this->social_fund_amount,
            'total_shared_liability_fund' => $this->total_shared_liability_fund,
            'total_group_fund' => $this->total_group_fund,
            'total_social_fund' => $this->total_social_fund,
            'is_active' => $this->is_active,

            'work_area' => new WorkAreaResource($this->workArea),
            'chairman' => new UserResource($this->chairman),
            'facilitator' => new UserResource($this->facilitator),
            'treasurer' => new UserResource($this->treasurer),

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
