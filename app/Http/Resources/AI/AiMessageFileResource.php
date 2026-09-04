<?php

namespace App\Http\Resources\AI;

use App\Http\Resources\FileResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AiMessageFileResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return ['id' => $this->id, 'file_id' => $this->file_id, 'created_at' => $this->created_at, 'updated_at' => $this->updated_at, 'file' => new FileResource($this->whenLoaded('file'))];
    }
}
