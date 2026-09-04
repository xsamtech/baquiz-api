<?php

namespace App\Http\Controllers\Api\AI;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\AI\AiMessageResource;
use App\Models\AI\AiMessage;

class AiMessageController extends ApiController
{
    protected string $modelClass = AiMessage::class;

    protected string $resourceClass = AiMessageResource::class;

    protected string $messageKey = 'ai_messages';

    protected array $relationships = ['toolCalls'];

    protected array $storeRules = ['role' => ['required', 'in:system,user,assistant,tool'], 'content' => ['required', 'string'], 'model' => ['sometimes', 'nullable', 'string', 'max:100'], 'prompt_tokens' => ['sometimes', 'nullable', 'integer', 'min:0'], 'completion_tokens' => ['sometimes', 'nullable', 'integer', 'min:0'], 'total_tokens' => ['sometimes', 'nullable', 'integer', 'min:0'], 'response_time_ms' => ['sometimes', 'nullable', 'integer', 'min:0'], 'error_message' => ['sometimes', 'nullable', 'string'], 'conversation_id' => ['required', 'integer', 'exists:ai_conversations,id']];

    protected array $updateRules = ['content' => ['sometimes', 'string'], 'model' => ['sometimes', 'nullable', 'string', 'max:100'], 'prompt_tokens' => ['sometimes', 'nullable', 'integer', 'min:0'], 'completion_tokens' => ['sometimes', 'nullable', 'integer', 'min:0'], 'total_tokens' => ['sometimes', 'nullable', 'integer', 'min:0'], 'response_time_ms' => ['sometimes', 'nullable', 'integer', 'min:0'], 'error_message' => ['sometimes', 'nullable', 'string']];
}
