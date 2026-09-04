<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlockedUserResource extends JsonResource
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
            'uuid' => $this->uuid,
            'complaint' => $this->complaint,
            'is_unlocked' => $this->is_unlocked,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'user_id' => $this->user_id,
            'reason_uuid' => $this->reason_uuid,
            'user' => new UserResource($this->whenLoaded('user')),
            'reason' => new ReasonResource($this->whenLoaded('reason')),
        ];
    }
}
