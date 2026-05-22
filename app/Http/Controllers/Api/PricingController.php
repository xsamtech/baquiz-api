<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PricingResource;
use App\Models\Pricing;

class PricingController extends ApiController
{
    protected string $modelClass = Pricing::class;

    protected string $resourceClass = PricingResource::class;

    protected string $messageKey = 'pricings';

    protected array $relationships = [];

    protected array $storeRules = [
        'pricing_name' => ['required', 'array'],
        'pricing_type' => ['sometimes', 'nullable', 'in:money, percentage'],
        'reason' => ['sometimes', 'nullable', 'in:clash_create, clash_participate, clash_boost, user_certfied, ad'],
        'pricing_cost' => ['sometimes', 'nullable', 'numeric'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'pricing_name' => ['sometimes', 'array'],
        'pricing_type' => ['sometimes', 'nullable', 'in:money, percentage'],
        'reason' => ['sometimes', 'nullable', 'in:clash_create, clash_participate, clash_boost, user_certfied, ad'],
        'pricing_cost' => ['sometimes', 'nullable', 'numeric'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
    ];
}
