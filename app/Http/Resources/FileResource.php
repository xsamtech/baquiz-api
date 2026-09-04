<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
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
            'file_name' => $this->file_name,
            'file_url' => $this->file_url,
            'file_description' => $this->file_description,
            'file_type' => $this->file_type,
            'mime_type' => $this->mime_type,
            'file_size' => $this->file_size,
            'width' => $this->width,
            'height' => $this->height,
            'duration' => $this->duration,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'question_id' => $this->question_id,
            'assertion_id' => $this->assertion_id,
            'answer_id' => $this->answer_id,
            'clash_id' => $this->clash_id,
            'user_id' => $this->user_id,
            'subject_id' => $this->subject_id,
            'field_id' => $this->field_id,
            'comment_id' => $this->comment_id,
            'domain_id' => $this->domain_id,
            'message_id' => $this->message_id,
            'question' => new QuestionResource($this->whenLoaded('question')),
            'assertion' => new AssertionResource($this->whenLoaded('assertion')),
            'answer' => new AnswerResource($this->whenLoaded('answer')),
            'clash' => new ClashResource($this->whenLoaded('clash')),
            'user' => new UserResource($this->whenLoaded('user')),
            'subject' => new SubjectResource($this->whenLoaded('subject')),
            'field' => new FieldResource($this->whenLoaded('field')),
            'comment' => new CommentResource($this->whenLoaded('comment')),
            'domain' => new DomainResource($this->whenLoaded('domain')),
            'message' => new MessageResource($this->whenLoaded('message')),
        ];
    }
}
