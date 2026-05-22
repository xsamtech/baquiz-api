<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
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
            'subject_name' => $this->subject_name,
            'subject_description' => $this->subject_description,
            'max_rating' => $this->max_rating,
            'weighting' => $this->weighting,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'level_id' => $this->level_id,
            'clash_id' => $this->clash_id,
            'level' => new LevelResource($this->whenLoaded('level')),
            'clash' => new ClashResource($this->whenLoaded('clash')),
        ];
    }
}
