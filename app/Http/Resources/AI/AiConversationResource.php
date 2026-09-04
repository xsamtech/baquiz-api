<?php

namespace App\Http\Resources\AI;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AiConversationResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return ['id' => $this->id, 'title' => $this->title, 'assistant' => $this->assistant, 'system_prompt' => $this->system_prompt, 'last_message_at' => $this->last_message_at, 'archived_at' => $this->archived_at, 'user_id' => $this->user_id, 'created_at' => $this->created_at, 'updated_at' => $this->updated_at, 'user' => new UserResource($this->whenLoaded('user'))];
    }
}
