<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PersonalAccessTokenResource;
use App\Models\PersonalAccessToken;

class PersonalAccessTokenController extends ApiController
{
    protected string $modelClass = PersonalAccessToken::class;

    protected string $resourceClass = PersonalAccessTokenResource::class;

    protected string $messageKey = 'personal_access_tokens';

    protected array $relationships = [];

    protected array $storeRules = [
        'tokenable_type' => ['required', 'string'],
        'tokenable_id' => ['required', 'integer'],
        'name' => ['required', 'string'],
        'token' => ['required', 'string'],
        'abilities' => ['sometimes', 'nullable', 'string'],
        'last_used_at' => ['sometimes', 'nullable', 'date'],
        'expires_at' => ['sometimes', 'nullable', 'date'],
    ];

    protected array $updateRules = [
        'tokenable_type' => ['sometimes', 'string'],
        'tokenable_id' => ['sometimes', 'integer'],
        'name' => ['sometimes', 'string'],
        'token' => ['sometimes', 'string'],
        'abilities' => ['sometimes', 'nullable', 'string'],
        'last_used_at' => ['sometimes', 'nullable', 'date'],
        'expires_at' => ['sometimes', 'nullable', 'date'],
    ];
}
