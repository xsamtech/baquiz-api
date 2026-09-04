<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\WebsiteResource;
use App\Models\Website;

class WebsiteController extends ApiController
{
    protected string $modelClass = Website::class;

    protected string $resourceClass = WebsiteResource::class;

    protected string $messageKey = 'websites';

    protected array $relationships = ['user'];

    protected array $storeRules = [
        'website_name' => ['required', 'string', 'max:255'],
        'website_url' => ['required', 'url'],
        'user_id' => ['required', 'integer', 'exists:users,id'],
    ];

    protected array $updateRules = [
        'website_name' => ['sometimes', 'string', 'max:255'],
        'website_url' => ['sometimes', 'url'],
        'user_id' => ['sometimes', 'integer', 'exists:users,id'],
    ];
}
