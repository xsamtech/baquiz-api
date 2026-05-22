<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\HashtagResource;
use App\Models\Hashtag;

class HashtagController extends ApiController
{
    protected string $modelClass = Hashtag::class;

    protected string $resourceClass = HashtagResource::class;

    protected string $messageKey = 'hashtags';

    protected array $relationships = [];

    protected array $storeRules = [
        'keyword' => ['required', 'string'],
    ];

    protected array $updateRules = [
        'keyword' => ['sometimes', 'string'],
    ];
}
