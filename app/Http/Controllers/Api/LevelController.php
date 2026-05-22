<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\LevelResource;
use App\Models\Level;

class LevelController extends ApiController
{
    protected string $modelClass = Level::class;

    protected string $resourceClass = LevelResource::class;

    protected string $messageKey = 'levels';

    protected array $relationships = [];

    protected array $storeRules = [
        'level_name' => ['required', 'array'],
        'min_score' => ['sometimes', 'nullable', 'integer'],
        'max_score' => ['sometimes', 'nullable', 'integer'],
        'icon' => ['sometimes', 'nullable', 'string'],
        'color' => ['sometimes', 'nullable', 'string'],
        'for_subject' => ['sometimes', 'nullable', 'boolean'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'level_name' => ['sometimes', 'array'],
        'min_score' => ['sometimes', 'nullable', 'integer'],
        'max_score' => ['sometimes', 'nullable', 'integer'],
        'icon' => ['sometimes', 'nullable', 'string'],
        'color' => ['sometimes', 'nullable', 'string'],
        'for_subject' => ['sometimes', 'nullable', 'boolean'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
    ];
}
