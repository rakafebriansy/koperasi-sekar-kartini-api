<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class UserResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'member_number' => $this->member_number,
            'identity_number' => $this->identity_number,
            'address' => $this->address,
            'birth_date' => $this->birth_date,
            'occupation' => $this->occupation,
            'identity_card_photo' => $this->identity_card_photo,
            'self_photo' => $this->self_photo,
            'member_card_photo' => $this->member_card_photo,
            'email_verified_at' => $this->email_verified_at,
            'role' => $this->role,
            'is_active' => $this->is_active,
            'work_area' => new WorkAreaResource($this->workArea),
            'group_id' => $this->group != null ? $this->group->id : null,
            'group_number' => (int) $this->group != null ? $this->group->number : null,
        ];
    }
}
