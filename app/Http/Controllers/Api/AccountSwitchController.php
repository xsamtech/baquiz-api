<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AccountSwitchResource;
use App\Models\AccountSwitch;

class AccountSwitchController extends ApiController
{
    protected string $modelClass = AccountSwitch::class;

    protected string $resourceClass = AccountSwitchResource::class;

    protected string $messageKey = 'account_switches';

    protected array $relationships = [
        'fromUser',
        'toUser',
    ];

    protected array $storeRules = [
        'ip_address' => ['sometimes', 'nullable', 'string'],
        'user_agent' => ['sometimes', 'nullable', 'string'],
        'session_id' => ['sometimes', 'nullable', 'string'],
        'latitude' => ['sometimes', 'nullable', 'numeric'],
        'longitude' => ['sometimes', 'nullable', 'numeric'],
        'city' => ['sometimes', 'nullable', 'string'],
        'region' => ['sometimes', 'nullable', 'string'],
        'country' => ['sometimes', 'nullable', 'string'],
        'from_user_id' => ['required', 'integer'],
        'to_user_id' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'ip_address' => ['sometimes', 'nullable', 'string'],
        'user_agent' => ['sometimes', 'nullable', 'string'],
        'session_id' => ['sometimes', 'nullable', 'string'],
        'latitude' => ['sometimes', 'nullable', 'numeric'],
        'longitude' => ['sometimes', 'nullable', 'numeric'],
        'city' => ['sometimes', 'nullable', 'string'],
        'region' => ['sometimes', 'nullable', 'string'],
        'country' => ['sometimes', 'nullable', 'string'],
        'from_user_id' => ['sometimes', 'integer'],
        'to_user_id' => ['sometimes', 'nullable', 'integer'],
    ];
}
