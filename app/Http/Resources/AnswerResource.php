<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
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
            'answer_content' => $this->answer_content,
            'time_taken' => $this->time_taken,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'question_id' => $this->question_id,
            'user_id' => $this->user_id,
            'question' => new QuestionResource($this->whenLoaded('question')),
            'user' => new UserResource($this->whenLoaded('user')),
            'hashtags' => HashtagResource::collection($this->whenLoaded('hashtags')),
        ];
    }
}
