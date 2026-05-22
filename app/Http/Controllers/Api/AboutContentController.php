<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AboutContentResource;
use App\Models\AboutContent;

class AboutContentController extends ApiController
{
    protected string $modelClass = AboutContent::class;

    protected string $resourceClass = AboutContentResource::class;

    protected string $messageKey = 'about_contents';

    protected array $relationships = [
        'aboutTitle',
    ];

    protected array $storeRules = [
        'subtitle' => ['sometimes', 'nullable', 'array'],
        'content' => ['required', 'array'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
        'about_title_id' => ['required', 'integer'],
    ];

    protected array $updateRules = [
        'subtitle' => ['sometimes', 'nullable', 'array'],
        'content' => ['sometimes', 'array'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
        'about_title_id' => ['sometimes', 'integer'],
    ];
}
