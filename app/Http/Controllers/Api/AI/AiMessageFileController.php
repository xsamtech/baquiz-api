<?php

namespace App\Http\Controllers\Api\AI;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\AI\AiMessageFileResource;
use App\Models\AI\AiMessageFile;

class AiMessageFileController extends ApiController
{
    protected string $modelClass = AiMessageFile::class;

    protected string $resourceClass = AiMessageFileResource::class;

    protected string $messageKey = 'ai_message_files';

    protected array $relationships = ['file'];

    protected array $storeRules = ['file_id' => ['required', 'integer', 'exists:files,id']];

    protected array $updateRules = ['file_id' => ['sometimes', 'integer', 'exists:files,id']];
}
