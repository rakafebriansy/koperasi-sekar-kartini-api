<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="WorkArea",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name_work_area", type="string", example="Wilayah Kerja A"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class WorkAreaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name_work_area' => $this->name_work_area,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
