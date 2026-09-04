<?php

namespace App\Http\Resources\AI;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AiToolCallResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return ['id' => $this->id, 'tool_name' => $this->tool_name, 'arguments' => $this->arguments, 'response' => $this->response, 'status' => $this->status, 'ai_message_id' => $this->ai_message_id, 'created_at' => $this->created_at, 'updated_at' => $this->updated_at];
    }
}
