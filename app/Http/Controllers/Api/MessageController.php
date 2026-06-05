<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\HandlesSocialContent;
use App\Http\Resources\CircleResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\UserResource;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
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
        'pollchoices.users',
    ];

    protected array $storeRules = [
        'message_content' => ['sometimes', 'nullable', 'string'],
        'event_title' => ['sometimes', 'nullable', 'string'],
        'event_description' => ['sometimes', 'nullable', 'string'],
        'event_start_at' => ['sometimes', 'nullable', 'date'],
        'event_end_at' => ['sometimes', 'nullable', 'date'],
        'event_place' => ['sometimes', 'nullable', 'string'],
        'answered_for' => ['sometimes', 'nullable', 'integer'],
        'type' => ['sometimes', 'nullable', 'in:text,poll,event,contact,voice_note,file,call_audio,call_video'],
        'call_type' => ['sometimes', 'nullable', 'in:outgoing,incoming,missed'],
        'status' => ['sometimes', 'nullable', 'in:read,unread'],
        'user_id' => ['sometimes', 'nullable', 'integer'],
        'addressee_user_id' => ['sometimes', 'nullable', 'integer'],
        'addressee_circle_id' => ['sometimes', 'nullable', 'integer'],
        'poll_choices' => ['sometimes', 'array'],
        'poll_choices.*.choice_content' => ['sometimes', 'nullable', 'string'],
        'poll_choices.*.image_url' => ['sometimes', 'nullable', 'string'],
    ];

    protected array $updateRules = [
        'message_content' => ['sometimes', 'nullable', 'string'],
        'event_title' => ['sometimes', 'nullable', 'string'],
        'event_description' => ['sometimes', 'nullable', 'string'],
        'event_start_at' => ['sometimes', 'nullable', 'date'],
        'event_end_at' => ['sometimes', 'nullable', 'date'],
        'event_place' => ['sometimes', 'nullable', 'string'],
        'answered_for' => ['sometimes', 'nullable', 'integer'],
        'type' => ['sometimes', 'nullable', 'in:text,poll,event,contact,voice_note,file,call_audio,call_video'],
        'call_type' => ['sometimes', 'nullable', 'in:outgoing,incoming,missed'],
        'status' => ['sometimes', 'nullable', 'in:read,unread'],
        'user_id' => ['sometimes', 'nullable', 'integer'],
        'addressee_user_id' => ['sometimes', 'nullable', 'integer'],
        'addressee_circle_id' => ['sometimes', 'nullable', 'integer'],
    ];

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->storeRules);

        $message = DB::transaction(function () use ($data): Message {
            $message = Message::query()->create(Arr::except($data, ['poll_choices']));

            if (($data['type'] ?? null) === 'poll' && isset($data['poll_choices'])) {
                $message->pollchoices()->createMany($data['poll_choices']);
            }

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

    public function searchPrivateConversation(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer'],
            'addressee_user_id' => ['required', 'integer'],
            'q' => ['sometimes', 'nullable', 'string'],
        ]);

        return $this->paginatedResponse(
            $this->privateConversationQuery((int) $data['user_id'], (int) $data['addressee_user_id'])
                ->tap(fn (Builder $query): Builder => $this->applySearch($query, $data['q'] ?? null))
        );
    }

    public function searchCircleConversation(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer'],
            'addressee_circle_id' => ['required', 'integer'],
            'q' => ['sometimes', 'nullable', 'string'],
        ]);

        return $this->paginatedResponse(
            $this->circleConversationQuery((int) $data['user_id'], (int) $data['addressee_circle_id'])
                ->tap(fn (Builder $query): Builder => $this->applySearch($query, $data['q'] ?? null))
        );
    }

    public function searchUserMessages(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer'],
            'q' => ['sometimes', 'nullable', 'string'],
        ]);

        return $this->paginatedResponse(
            $this->userMessagesQuery((int) $data['user_id'])
                ->tap(fn (Builder $query): Builder => $this->applySearch($query, $data['q'] ?? null))
        );
    }

    public function conversations(int $userId): JsonResponse
    {
        $messages = $this->userMessagesQuery($userId)
            ->latest('id')
            ->get();

        $conversations = $messages
            ->groupBy(fn (Message $message): string => $message->addressee_circle_id
                ? 'circle:'.$message->addressee_circle_id
                : 'user:'.$this->otherUserId($message, $userId))
            ->map(fn ($messages, string $key): array => $this->conversationSummary($messages, $key, $userId))
            ->sortByDesc(fn (array $conversation): int => $conversation['last_message']->id)
            ->values();

        return $this->handleResponse($conversations, Lang::get('api.messages.index'));
    }

    public function privateConversation(int $userId, int $addresseeUserId): JsonResponse
    {
        return $this->paginatedResponse($this->privateConversationQuery($userId, $addresseeUserId));
    }

    public function circleConversation(int $userId, int $addresseeCircleId): JsonResponse
    {
        return $this->paginatedResponse($this->circleConversationQuery($userId, $addresseeCircleId));
    }

    protected function paginatedResponse(Builder $query): JsonResponse
    {
        $messages = $query->latest('id')->paginate(20);

        return $this->handleResponse(
            MessageResource::collection($messages),
            Lang::get('api.messages.index'),
            $messages->lastPage(),
            $messages->total()
        );
    }

    protected function userMessagesQuery(int $userId): Builder
    {
        return Message::query()
            ->with($this->relationships)
            ->where(function (Builder $query) use ($userId): void {
                $query->where('user_id', $userId)
                    ->orWhere('addressee_user_id', $userId)
                    ->orWhereHas('addresseeCircle.members', fn (Builder $query): Builder => $query->whereKey($userId));
            });
    }

    protected function privateConversationQuery(int $userId, int $addresseeUserId): Builder
    {
        return Message::query()
            ->with($this->relationships)
            ->whereNull('addressee_circle_id')
            ->where(function (Builder $query) use ($userId, $addresseeUserId): void {
                $query->where(function (Builder $query) use ($userId, $addresseeUserId): void {
                    $query->where('user_id', $userId)
                        ->where('addressee_user_id', $addresseeUserId);
                })->orWhere(function (Builder $query) use ($userId, $addresseeUserId): void {
                    $query->where('user_id', $addresseeUserId)
                        ->where('addressee_user_id', $userId);
                });
            });
    }

    protected function circleConversationQuery(int $userId, int $addresseeCircleId): Builder
    {
        return Message::query()
            ->with($this->relationships)
            ->where('addressee_circle_id', $addresseeCircleId)
            ->where(function (Builder $query) use ($userId): void {
                $query->where('user_id', $userId)
                    ->orWhereHas('addresseeCircle.members', fn (Builder $query): Builder => $query->whereKey($userId));
            });
    }

    protected function applySearch(Builder $query, ?string $search): Builder
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($search): void {
            $query->where('message_content', 'like', "%{$search}%")
                ->orWhere('event_title', 'like', "%{$search}%")
                ->orWhere('event_description', 'like', "%{$search}%")
                ->orWhere('event_place', 'like', "%{$search}%");
        });
    }

    protected function conversationSummary(Collection $messages, string $key, int $userId): array
    {
        /** @var Message $lastMessage */
        $lastMessage = $messages->first();
        [$type] = explode(':', $key, 2);

        return [
            'type' => $type,
            'addressee_user_id' => $type === 'user' ? $this->otherUserId($lastMessage, $userId) : null,
            'addressee_circle_id' => $type === 'circle' ? $lastMessage->addressee_circle_id : null,
            'user' => $type === 'user' ? new UserResource($this->otherUser($lastMessage, $userId)) : null,
            'circle' => $type === 'circle' ? new CircleResource($lastMessage->addresseeCircle) : null,
            'last_message' => new MessageResource($lastMessage),
            'unread_count' => $messages->filter(fn (Message $message): bool => $this->isUnreadForUser($message, $userId))->count(),
        ];
    }

    protected function otherUserId(Message $message, int $userId): ?int
    {
        return $message->user_id === $userId ? $message->addressee_user_id : $message->user_id;
    }

    protected function otherUser(Message $message, int $userId): ?User
    {
        return $message->user_id === $userId ? $message->addresseeUser : $message->user;
    }

    protected function isUnreadForUser(Message $message, int $userId): bool
    {
        if ($message->status !== 'unread') {
            return false;
        }

        return $message->addressee_user_id === $userId
            || ($message->addressee_circle_id !== null && $message->user_id !== $userId);
    }
}
