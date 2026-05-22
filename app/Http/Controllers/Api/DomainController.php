<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\DomainResource;
use App\Models\Domain;

class DomainController extends ApiController
{
    protected string $modelClass = Domain::class;

    protected string $resourceClass = DomainResource::class;

    protected string $messageKey = 'domains';

    protected array $relationships = [];

    protected array $storeRules = [
        'domain_name' => ['required', 'array'],
        'domain_description' => ['sometimes', 'nullable', 'array'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'domain_name' => ['sometimes', 'array'],
        'domain_description' => ['sometimes', 'nullable', 'array'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
    ];
}
