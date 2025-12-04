<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Group",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="number", type="string", example="GRP001"),
 *     @OA\Property(property="description", type="string", example="Kelompok tani sejahtera", nullable=true),
 *     @OA\Property(property="shared_liability_fund_amount", type="integer", example=50000),
 *     @OA\Property(property="group_fund_amount", type="integer", example=30000),
 *     @OA\Property(property="social_fund_amount", type="integer", example=20000),
 *     @OA\Property(property="total_shared_liability_fund", type="integer", example=500000),
 *     @OA\Property(property="total_group_fund", type="integer", example=300000),
 *     @OA\Property(property="total_social_fund", type="integer", example=200000),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="work_area_id", type="integer", example=1, nullable=true),
 *     @OA\Property(property="chairman_id", type="integer", example=1, nullable=true),
 *     @OA\Property(property="facilitator_id", type="integer", example=2, nullable=true),
 *     @OA\Property(property="treasurer_id", type="integer", example=3, nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class GroupResource extends JsonResource
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
