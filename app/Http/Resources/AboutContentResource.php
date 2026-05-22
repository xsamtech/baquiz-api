<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutContentResource extends JsonResource
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
            'subtitle' => $this->subtitle,
            'subtitle_translations' => $this->getTranslations('subtitle'),
            'content' => $this->content,
            'content_translations' => $this->getTranslations('content'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'about_title_id' => $this->about_title_id,
            'aboutTitle' => new AboutTitleResource($this->whenLoaded('aboutTitle')),
        ];
    }
}
