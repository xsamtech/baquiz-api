<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutDashResource extends JsonResource
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
            'dash_content' => $this->dash_content,
            'dash_content_translations' => $this->getTranslations('dash_content'),
            'belongs_to' => $this->belongs_to,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'about_content_id' => $this->about_content_id,
            'aboutContent' => new AboutContentResource($this->whenLoaded('aboutContent')),
        ];
    }
}
