<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'meeting_type'  => $this->meeting_type,
            'date'          => $this->date,
            'time'          => $this->time,
            'location'      => $this->location,
            'photo'         => $this->photo,
            'description'   => $this->description,
            'group_id'      => $this->group_id,
            'group'         => new GroupResource($this->group),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];

    }
}
