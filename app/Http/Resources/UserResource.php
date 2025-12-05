<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="User",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Rahmat Admin"),
 *     @OA\Property(property="phone_number", type="string", example="081200000001"),
 *     @OA\Property(property="member_number", type="string", example="ADM-001"),
 *     @OA\Property(property="identity_number", type="string", example="3201111111110001"),
 *     @OA\Property(property="address", type="string", example="Jl. Anggur No. 10, Sumbersari, Jember"),
 *     @OA\Property(property="role", type="string", example="admin"),
 *     @OA\Property(property="is_verified", type="boolean", example=true),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(
 *         property="work_area",
 *         ref="#/components/schemas/WorkArea"
 *     ),
 *     @OA\Property(
 *         property="group",
 *         ref="#/components/schemas/Group"
 *     )
 * )
 */
class UserResource extends JsonResource
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
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'member_number' => $this->member_number,
            'identity_number' => $this->identity_number,
            'address' => $this->address,
            'occupation' => $this->occupation,
            'identity_card_photo' => $this->identity_card_photo,
            'self_photo' => $this->self_photo,
            'member_card_photo' => $this->member_card_photo,
            'email_verified_at' => $this->email_verified_at,
            'role' => $this->role,
            'is_verified' => $this->is_verified,
            'is_active' => $this->is_active,
            'work_area' => new WorkAreaResource($this->workArea),
            'group' => new GroupResource($this->memberGroup),
        ];
    }
}
