<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PromoCodeResource;
use App\Models\PromoCode;

class PromoCodeController extends ApiController
{
    protected string $modelClass = PromoCode::class;

    protected string $resourceClass = PromoCodeResource::class;

    protected string $messageKey = 'promo_codes';

    protected array $relationships = [
        'user',
    ];

    protected array $storeRules = [
        'code' => ['required', 'string'],
        'validity' => ['required', 'integer'],
        'status' => ['sometimes', 'nullable', 'in:active, expired'],
        'user_id' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'code' => ['sometimes', 'string'],
        'validity' => ['sometimes', 'integer'],
        'status' => ['sometimes', 'nullable', 'in:active, expired'],
        'user_id' => ['sometimes', 'nullable', 'integer'],
    ];
}
