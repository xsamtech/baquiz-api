<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SessionResource;
use App\Models\Session;

class SessionController extends ApiController
{
    protected string $modelClass = Session::class;

    protected string $resourceClass = SessionResource::class;

    protected string $messageKey = 'sessions';

    protected array $relationships = [
        'user',
    ];

    protected array $storeRules = [
        'ip_address' => ['sometimes', 'nullable', 'string'],
        'user_agent' => ['sometimes', 'nullable', 'string'],
        'payload' => ['sometimes', 'nullable', 'string'],
        'last_activity' => ['sometimes', 'nullable', 'integer'],
        'latitude' => ['sometimes', 'nullable', 'numeric'],
        'longitude' => ['sometimes', 'nullable', 'numeric'],
        'city' => ['sometimes', 'nullable', 'string'],
        'region' => ['sometimes', 'nullable', 'string'],
        'country' => ['sometimes', 'nullable', 'string'],
        'user_id' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'ip_address' => ['sometimes', 'nullable', 'string'],
        'user_agent' => ['sometimes', 'nullable', 'string'],
        'payload' => ['sometimes', 'nullable', 'string'],
        'last_activity' => ['sometimes', 'nullable', 'integer'],
        'latitude' => ['sometimes', 'nullable', 'numeric'],
        'longitude' => ['sometimes', 'nullable', 'numeric'],
        'city' => ['sometimes', 'nullable', 'string'],
        'region' => ['sometimes', 'nullable', 'string'],
        'country' => ['sometimes', 'nullable', 'string'],
        'user_id' => ['sometimes', 'nullable', 'integer'],
    ];
}
