<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\HandlesSocialContent;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class MessageController extends ApiController
{
    use HandlesSocialContent;

    protected string $modelClass = Message::class;

    protected string $resourceClass = MessageResource::class;

    protected string $messageKey = 'messages';

    protected array $relationships = [
        'user',
        'addresseeUser',
        'addresseeCircle',
        'hashtags',
    ];

    protected array $storeRules = [
        'message_content' => ['sometimes', 'nullable', 'string'],
        'answered_for' => ['sometimes', 'nullable', 'integer'],
        'status' => ['sometimes', 'nullable', 'in:read,unread'],
        'user_id' => ['sometimes', 'nullable', 'integer'],
        'addressee_user_id' => ['sometimes', 'nullable', 'integer'],
        'addressee_circle_id' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'message_content' => ['sometimes', 'nullable', 'string'],
        'answered_for' => ['sometimes', 'nullable', 'integer'],
        'status' => ['sometimes', 'nullable', 'in:read,unread'],
        'user_id' => ['sometimes', 'nullable', 'integer'],
        'addressee_user_id' => ['sometimes', 'nullable', 'integer'],
        'addressee_circle_id' => ['sometimes', 'nullable', 'integer'],
    ];

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->storeRules);

        $message = DB::transaction(function () use ($data): Message {
            $message = Message::query()->create($data);
            $this->syncHashtags($message, 'message_content');
            $this->notifyMentionedUsers($message->user_id, $message->message_content, [
                'message_id' => $message->id,
            ]);

            return $message;
        });

        return $this->handleResponse(
            new MessageResource($message->load($this->relationships)),
            Lang::get('api.messages.store')
        );
    }
}
