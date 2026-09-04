<?php

namespace App\Http\Resources\AI;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AiMessageResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return ['id' => $this->id, 'role' => $this->role, 'content' => $this->content, 'model' => $this->model, 'prompt_tokens' => $this->prompt_tokens, 'completion_tokens' => $this->completion_tokens, 'total_tokens' => $this->total_tokens, 'response_time_ms' => $this->response_time_ms, 'error_message' => $this->error_message, 'conversation_id' => $this->conversation_id, 'created_at' => $this->created_at, 'updated_at' => $this->updated_at, 'tool_calls' => AiToolCallResource::collection($this->whenLoaded('toolCalls'))];
    }
}
