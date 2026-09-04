<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BlockedUserResource;
use App\Models\BlockedUser;

class BlockedUserController extends ApiController
{
    protected string $modelClass = BlockedUser::class;

    protected string $resourceClass = BlockedUserResource::class;

    protected string $messageKey = 'blocked_users';

    protected array $relationships = [
        'user',
        'reason',
    ];

    protected array $storeRules = [
        'complaint' => ['sometimes', 'nullable', 'string'],
        'is_unlocked' => ['sometimes', 'nullable', 'boolean'],
        'user_id' => ['required', 'integer'],
        'reason_uuid' => ['required', 'uuid', 'exists:reasons,uuid'],
    ];

    protected array $updateRules = [
        'complaint' => ['sometimes', 'nullable', 'string'],
        'is_unlocked' => ['sometimes', 'nullable', 'boolean'],
        'user_id' => ['sometimes', 'integer'],
        'reason_uuid' => ['sometimes', 'uuid', 'exists:reasons,uuid'],
    ];
}
