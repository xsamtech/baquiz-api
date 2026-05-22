<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CircleResource;
use App\Models\Circle;

class CircleController extends ApiController
{
    protected string $modelClass = Circle::class;

    protected string $resourceClass = CircleResource::class;

    protected string $messageKey = 'circles';

    protected array $relationships = [
        'user',
    ];

    protected array $storeRules = [
        'circle_name' => ['required', 'string'],
        'profile_photo' => ['sometimes', 'nullable', 'string'],
        'user_id' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'circle_name' => ['sometimes', 'string'],
        'profile_photo' => ['sometimes', 'nullable', 'string'],
        'user_id' => ['sometimes', 'nullable', 'integer'],
    ];
}
