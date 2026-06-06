<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HashtagResource extends JsonResource
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
            'keyword' => $this->keyword,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'clashs' => ClashResource::collection($this->whenLoaded('clashs')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'messages' => MessageResource::collection($this->whenLoaded('messages')),
            'questions' => QuestionResource::collection($this->whenLoaded('questions')),
            'assertions' => AssertionResource::collection($this->whenLoaded('assertions')),
            'answers' => AnswerResource::collection($this->whenLoaded('answers')),
        ];
    }
}
