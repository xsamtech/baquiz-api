<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PollchoiceResource extends JsonResource
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
            'choice_content' => $this->choice_content,
            'image_url' => $this->image_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'message_id' => $this->message_id,
            // 'message' => new MessageResource($this->whenLoaded('message')),
            'users' => UserResource::collection($this->whenLoaded('users')),
        ];
    }
}
