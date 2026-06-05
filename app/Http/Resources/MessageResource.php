<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'message_content' => $this->message_content,
            'event_title' => $this->event_title,
            'event_description' => $this->event_description,
            'event_start_at' => $this->event_start_at,
            'event_end_at' => $this->event_end_at,
            'event_place' => $this->event_place,
            'answered_for' => $this->answered_for,
            'type' => $this->type,
            'call_type' => $this->call_type,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'user_id' => $this->user_id,
            'addressee_user_id' => $this->addressee_user_id,
            'addressee_circle_id' => $this->addressee_circle_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'addresseeUser' => new UserResource($this->whenLoaded('addresseeUser')),
            'addresseeCircle' => new CircleResource($this->whenLoaded('addresseeCircle')),
            'hashtags' => HashtagResource::collection($this->whenLoaded('hashtags')),
            'poll_choices' => PollchoiceResource::collection($this->whenLoaded('pollchoices')),
        ];
    }
}
