<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\RecommendationResource;
use App\Models\Recommendation;

class RecommendationController extends ApiController
{
    protected string $modelClass = Recommendation::class;

    protected string $resourceClass = RecommendationResource::class;

    protected string $messageKey = 'recommendations';

    protected array $relationships = [
        'domain',
        'competence',
        'level',
    ];

    protected array $storeRules = [
        'recommendation_content' => ['required', 'string'],
        'domain_id' => ['sometimes', 'nullable', 'integer'],
        'competence_id' => ['sometimes', 'nullable', 'integer'],
        'level_id' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'recommendation_content' => ['sometimes', 'string'],
        'domain_id' => ['sometimes', 'nullable', 'integer'],
        'competence_id' => ['sometimes', 'nullable', 'integer'],
        'level_id' => ['sometimes', 'nullable', 'integer'],
    ];
}
