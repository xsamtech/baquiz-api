<?php

namespace App\Http\Resources\AI;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AiSettingResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return ['id' => $this->id, 'provider' => $this->provider, 'model' => $this->model, 'temperature' => $this->temperature, 'max_tokens' => $this->max_tokens, 'stream' => $this->stream, 'enabled' => $this->enabled, 'created_at' => $this->created_at, 'updated_at' => $this->updated_at];
    }
}
