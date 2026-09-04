<?php

namespace App\Http\Controllers\Api\AI;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\AI\AiToolCallResource;
use App\Models\AI\AiToolCall;

class AiToolCallController extends ApiController
{
    protected string $modelClass = AiToolCall::class;

    protected string $resourceClass = AiToolCallResource::class;

    protected string $messageKey = 'ai_tool_calls';

    protected array $relationships = [];

    protected array $storeRules = ['tool_name' => ['required', 'string', 'max:100'], 'arguments' => ['sometimes', 'nullable', 'array'], 'response' => ['sometimes', 'nullable', 'array'], 'status' => ['sometimes', 'nullable', 'in:pending,success,failed'], 'ai_message_id' => ['required', 'integer', 'exists:ai_messages,id']];

    protected array $updateRules = ['tool_name' => ['sometimes', 'string', 'max:100'], 'arguments' => ['sometimes', 'nullable', 'array'], 'response' => ['sometimes', 'nullable', 'array'], 'status' => ['sometimes', 'nullable', 'in:pending,success,failed']];
}
