<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'member_number' => $this->member_number,
            'identity_number' => $this->identity_number,
            'birth_date' => $this->birth_date,
            'phone_number' => $this->phone_number,
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
            'group' => new MemberGroupResource($this->memberGroup),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
