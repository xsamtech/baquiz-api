<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\HistoryResource;
use App\Models\History;

class HistoryController extends ApiController
{
    protected string $modelClass = History::class;

    protected string $resourceClass = HistoryResource::class;

    protected string $messageKey = 'histories';

    protected array $relationships = [
        'user',
    ];

    protected array $storeRules = [
        'word' => ['sometimes', 'nullable', 'string'],
        'entity' => ['sometimes', 'nullable', 'in:clash, course, subject, user'],
        'entity_id' => ['sometimes', 'nullable', 'integer'],
        'action' => ['sometimes', 'nullable', 'in:search, view, reaction, comment, report'],
        'user_id' => ['required', 'integer'],
    ];

    protected array $updateRules = [
        'word' => ['sometimes', 'nullable', 'string'],
        'entity' => ['sometimes', 'nullable', 'in:clash, course, subject, user'],
        'entity_id' => ['sometimes', 'nullable', 'integer'],
        'action' => ['sometimes', 'nullable', 'in:search, view, reaction, comment, report'],
        'user_id' => ['sometimes', 'integer'],
    ];
}
