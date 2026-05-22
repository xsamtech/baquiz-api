<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AboutTitleResource;
use App\Models\AboutTitle;

class AboutTitleController extends ApiController
{
    protected string $modelClass = AboutTitle::class;

    protected string $resourceClass = AboutTitleResource::class;

    protected string $messageKey = 'about_titles';

    protected array $relationships = [
        'aboutSubject',
    ];

    protected array $storeRules = [
        'title' => ['required', 'array'],
        'alias' => ['sometimes', 'nullable', 'string'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
        'about_subject_id' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'title' => ['sometimes', 'array'],
        'alias' => ['sometimes', 'nullable', 'string'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
        'about_subject_id' => ['sometimes', 'nullable', 'integer'],
    ];
}
