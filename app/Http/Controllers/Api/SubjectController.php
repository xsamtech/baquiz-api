<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SubjectResource;
use App\Models\Subject;

class SubjectController extends ApiController
{
    protected string $modelClass = Subject::class;

    protected string $resourceClass = SubjectResource::class;

    protected string $messageKey = 'subjects';

    protected array $relationships = [
        'level',
        'clash',
    ];

    protected array $storeRules = [
        'subject_name' => ['required', 'string'],
        'subject_description' => ['sometimes', 'nullable', 'string'],
        'max_rating' => ['sometimes', 'nullable', 'numeric'],
        'weighting' => ['sometimes', 'nullable', 'numeric'],
        'status' => ['sometimes', 'nullable', 'in:created, activated, disabled, blocked, deleted'],
        'level_id' => ['sometimes', 'nullable', 'integer'],
        'clash_id' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'subject_name' => ['sometimes', 'string'],
        'subject_description' => ['sometimes', 'nullable', 'string'],
        'max_rating' => ['sometimes', 'nullable', 'numeric'],
        'weighting' => ['sometimes', 'nullable', 'numeric'],
        'status' => ['sometimes', 'nullable', 'in:created, activated, disabled, blocked, deleted'],
        'level_id' => ['sometimes', 'nullable', 'integer'],
        'clash_id' => ['sometimes', 'nullable', 'integer'],
    ];
}
