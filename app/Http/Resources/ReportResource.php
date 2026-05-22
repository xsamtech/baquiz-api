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
            'id' => $this->id,
            'entity' => $this->entity,
            'entity_id' => $this->entity_id,
            'report_content' => $this->report_content,
            'muted' => $this->muted,
            'for_user_id' => $this->for_user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'reason_id' => $this->reason_id,
            'user_id' => $this->user_id,
            'reason' => new ReasonResource($this->whenLoaded('reason')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
