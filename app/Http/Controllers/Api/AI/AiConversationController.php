<?php

namespace App\Http\Controllers\Api\AI;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\AI\AiConversationResource;
use App\Models\AI\AiConversation;

class AiConversationController extends ApiController
{
    protected string $modelClass = AiConversation::class;

    protected string $resourceClass = AiConversationResource::class;

    protected string $messageKey = 'ai_conversations';

    protected array $relationships = ['user'];

    protected array $storeRules = ['title' => ['required', 'string', 'max:255'], 'assistant' => ['required', 'string', 'max:50'], 'system_prompt' => ['sometimes', 'nullable', 'string'], 'last_message_at' => ['sometimes', 'nullable', 'date'], 'archived_at' => ['sometimes', 'nullable', 'date'], 'user_id' => ['required', 'integer', 'exists:users,id']];

    protected array $updateRules = ['title' => ['sometimes', 'string', 'max:255'], 'assistant' => ['sometimes', 'string', 'max:50'], 'system_prompt' => ['sometimes', 'nullable', 'string'], 'last_message_at' => ['sometimes', 'nullable', 'date'], 'archived_at' => ['sometimes', 'nullable', 'date']];
}
