<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'number' => (int) $this->number,
            'description' => $this->description,
            'shared_liability_fund_amount' => (int) $this->shared_liability_fund_amount,
            'group_fund_amount' => (int) $this->group_fund_amount,
            'social_fund_amount' => (int) $this->social_fund_amount,

            'work_area' => new WorkAreaResource($this->workArea),
            'chairman' => new UserResource($this->chairman),
            'facilitator' => new UserResource($this->facilitator),

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
