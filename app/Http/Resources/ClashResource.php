<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClashResource extends JsonResource
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
            'clash_code' => $this->clash_code,
            'clash_description' => $this->clash_description,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'price' => $this->price,
            'currency' => $this->currency,
            'is_competition' => $this->is_competition,
            'type' => $this->type,
            'last_boost_at' => $this->last_boost_at,
            'boost_type' => $this->boost_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'field_id' => $this->field_id,
            'user_id' => $this->user_id,
            'field' => new FieldResource($this->whenLoaded('field')),
            'user' => new UserResource($this->whenLoaded('user')),
            'hashtags' => HashtagResource::collection($this->whenLoaded('hashtags')),
        ];
    }
}
