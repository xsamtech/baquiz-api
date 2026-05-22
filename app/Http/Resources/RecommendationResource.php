<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecommendationResource extends JsonResource
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
            'recommendation_content' => $this->recommendation_content,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'domain_id' => $this->domain_id,
            'competence_id' => $this->competence_id,
            'level_id' => $this->level_id,
            'domain' => new DomainResource($this->whenLoaded('domain')),
            'competence' => new CompetenceResource($this->whenLoaded('competence')),
            'level' => new LevelResource($this->whenLoaded('level')),
        ];
    }
}
