<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CompetenceResource;
use App\Models\Competence;

class CompetenceController extends ApiController
{
    protected string $modelClass = Competence::class;

    protected string $resourceClass = CompetenceResource::class;

    protected string $messageKey = 'competences';

    protected array $relationships = [];

    protected array $storeRules = [
        'competence_name' => ['required', 'array'],
        'competence_description' => ['sometimes', 'nullable', 'array'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'competence_name' => ['sometimes', 'array'],
        'competence_description' => ['sometimes', 'nullable', 'array'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
    ];
}
