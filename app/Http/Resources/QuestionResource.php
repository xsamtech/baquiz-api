<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            'question_content' => $this->question_content,
            'expected_time' => $this->expected_time,
            'percentages_removed' => $this->percentages_removed,
            'max_rating' => $this->max_rating,
            'correct_assertions_count' => $this->correct_assertions_count,
            'assertion_rating' => $this->assertion_rating,
            'assertions_combination_required' => $this->assertions_combination_required,
            'type' => $this->type,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'subject_id' => $this->subject_id,
            'domain_id' => $this->domain_id,
            'subject' => new SubjectResource($this->whenLoaded('subject')),
            'domain' => new DomainResource($this->whenLoaded('domain')),
            'hashtags' => HashtagResource::collection($this->whenLoaded('hashtags')),
        ];
    }
}
