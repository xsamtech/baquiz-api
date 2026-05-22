<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'comment_content' => $this->comment_content,
            'answered_for' => $this->answered_for,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'clash_id' => $this->clash_id,
            'user_id' => $this->user_id,
            'clash' => new ClashResource($this->whenLoaded('clash')),
            'user' => new UserResource($this->whenLoaded('user')),
            'hashtags' => HashtagResource::collection($this->whenLoaded('hashtags')),
        ];
    }
}
