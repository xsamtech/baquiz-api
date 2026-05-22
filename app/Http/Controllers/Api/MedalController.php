<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\MedalResource;
use App\Models\Medal;

class MedalController extends ApiController
{
    protected string $modelClass = Medal::class;

    protected string $resourceClass = MedalResource::class;

    protected string $messageKey = 'medals';

    protected array $relationships = [];

    protected array $storeRules = [
        'medal_type' => ['required', 'in:elite, prestige, ultima'],
        'medal_color' => ['required', 'string'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'medal_type' => ['sometimes', 'in:elite, prestige, ultima'],
        'medal_color' => ['sometimes', 'string'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
    ];
}
