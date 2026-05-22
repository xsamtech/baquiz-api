<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PricingDescriptionResource;
use App\Models\PricingDescription;

class PricingDescriptionController extends ApiController
{
    protected string $modelClass = PricingDescription::class;

    protected string $resourceClass = PricingDescriptionResource::class;

    protected string $messageKey = 'pricing_descriptions';

    protected array $relationships = [
        'pricing',
    ];

    protected array $storeRules = [
        'description_title' => ['required', 'array'],
        'description_content' => ['sometimes', 'nullable', 'array'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
        'pricing_id' => ['required', 'integer'],
    ];

    protected array $updateRules = [
        'description_title' => ['sometimes', 'array'],
        'description_content' => ['sometimes', 'nullable', 'array'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
        'pricing_id' => ['sometimes', 'integer'],
    ];
}
