<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;

class SubscriptionController extends ApiController
{
    protected string $modelClass = Subscription::class;

    protected string $resourceClass = SubscriptionResource::class;

    protected string $messageKey = 'subscriptions';

    protected array $relationships = [
        'user',
        'follower',
    ];

    protected array $storeRules = [
        'user_id' => ['required', 'integer'],
        'follower_id' => ['required', 'integer'],
        'granted' => ['sometimes', 'nullable', 'boolean'],
    ];

    protected array $updateRules = [
        'user_id' => ['sometimes', 'integer'],
        'follower_id' => ['sometimes', 'integer'],
        'granted' => ['sometimes', 'nullable', 'boolean'],
    ];
}
