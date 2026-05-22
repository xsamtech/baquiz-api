<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PricingDescriptionResource extends JsonResource
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
            'description_title' => $this->description_title,
            'description_title_translations' => $this->getTranslations('description_title'),
            'description_content' => $this->description_content,
            'description_content_translations' => $this->getTranslations('description_content'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'pricing_id' => $this->pricing_id,
            'pricing' => new PricingResource($this->whenLoaded('pricing')),
        ];
    }
}
