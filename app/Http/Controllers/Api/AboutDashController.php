<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AboutDashResource;
use App\Models\AboutDash;

class AboutDashController extends ApiController
{
    protected string $modelClass = AboutDash::class;

    protected string $resourceClass = AboutDashResource::class;

    protected string $messageKey = 'about_dashes';

    protected array $relationships = [
        'aboutContent',
    ];

    protected array $storeRules = [
        'dash_content' => ['required', 'array'],
        'belongs_to' => ['sometimes', 'nullable', 'integer'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
        'about_content_id' => ['required', 'integer'],
    ];

    protected array $updateRules = [
        'dash_content' => ['sometimes', 'array'],
        'belongs_to' => ['sometimes', 'nullable', 'integer'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
        'about_content_id' => ['sometimes', 'integer'],
    ];
}
