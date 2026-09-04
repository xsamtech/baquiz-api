<?php

namespace App\Http\Controllers\Api\AI;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\AI\AiSettingResource;
use App\Models\AI\AISetting;

class AiSettingController extends ApiController
{
    protected string $modelClass = AISetting::class;

    protected string $resourceClass = AiSettingResource::class;

    protected string $messageKey = 'ai_settings';

    protected array $relationships = [];

    protected array $storeRules = ['provider' => ['required', 'string', 'max:50'], 'model' => ['required', 'string', 'max:100'], 'temperature' => ['required', 'numeric', 'between:0,2'], 'max_tokens' => ['required', 'integer', 'min:1'], 'stream' => ['required', 'boolean'], 'enabled' => ['required', 'boolean']];

    protected array $updateRules = ['provider' => ['sometimes', 'string', 'max:50'], 'model' => ['sometimes', 'string', 'max:100'], 'temperature' => ['sometimes', 'numeric', 'between:0,2'], 'max_tokens' => ['sometimes', 'integer', 'min:1'], 'stream' => ['sometimes', 'boolean'], 'enabled' => ['sometimes', 'boolean']];
}
