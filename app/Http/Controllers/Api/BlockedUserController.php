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
        'aboutTitle',
    ];

    protected array $storeRules = [
        'complaint' => ['sometimes', 'nullable', 'string'],
        'is_unlocked' => ['sometimes', 'nullable', 'boolean'],
        'user_id' => ['required', 'integer'],
        'about_title_id' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'complaint' => ['sometimes', 'nullable', 'string'],
        'is_unlocked' => ['sometimes', 'nullable', 'boolean'],
        'user_id' => ['sometimes', 'integer'],
        'about_title_id' => ['sometimes', 'nullable', 'integer'],
    ];
}
