<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AboutSubjectResource;
use App\Models\AboutSubject;

class AboutSubjectController extends ApiController
{
    protected string $modelClass = AboutSubject::class;

    protected string $resourceClass = AboutSubjectResource::class;

    protected string $messageKey = 'about_subjects';

    protected array $relationships = [];

    protected array $storeRules = [
        'subject' => ['sometimes', 'nullable', 'array'],
        'subject_description' => ['required', 'array'],
        'status' => ['sometimes', 'nullable', 'in:selected, rejected'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'subject' => ['sometimes', 'nullable', 'array'],
        'subject_description' => ['sometimes', 'array'],
        'status' => ['sometimes', 'nullable', 'in:selected, rejected'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
    ];
}
