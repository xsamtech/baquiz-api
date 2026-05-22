<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'type' => $this->type,
            'is_read' => $this->is_read,
            'from_user_id' => $this->from_user_id,
            'to_user_id' => $this->to_user_id,
            'clash_id' => $this->clash_id,
            'comment_id' => $this->comment_id,
            'message_id' => $this->message_id,
            'question_id' => $this->question_id,
            'assertion_id' => $this->assertion_id,
            'answer_id' => $this->answer_id,
            'fromUser' => new UserResource($this->whenLoaded('fromUser')),
            'toUser' => new UserResource($this->whenLoaded('toUser')),
            'clash' => new ClashResource($this->whenLoaded('clash')),
            'comment' => new CommentResource($this->whenLoaded('comment')),
            'message' => new MessageResource($this->whenLoaded('message')),
            'question' => new QuestionResource($this->whenLoaded('question')),
            'assertion' => new AssertionResource($this->whenLoaded('assertion')),
            'answer' => new AnswerResource($this->whenLoaded('answer')),
        ];
    }
}
