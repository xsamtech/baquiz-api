<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PaymentResource;
use App\Models\Payment;

class PaymentController extends ApiController
{
    protected string $modelClass = Payment::class;

    protected string $resourceClass = PaymentResource::class;

    protected string $messageKey = 'payments';

    protected array $relationships = [
        'user',
    ];

    protected array $storeRules = [
        'reference' => ['sometimes', 'nullable', 'string'],
        'provider_reference' => ['sometimes', 'nullable', 'string'],
        'order_number' => ['sometimes', 'nullable', 'string'],
        'amount' => ['sometimes', 'nullable', 'numeric'],
        'amount_customer' => ['sometimes', 'nullable', 'numeric'],
        'phone' => ['sometimes', 'nullable', 'string'],
        'currency' => ['sometimes', 'nullable', 'string'],
        'channel' => ['sometimes', 'nullable', 'string'],
        'type' => ['required', 'integer'],
        'status' => ['sometimes', 'nullable', 'integer'],
        'reason' => ['sometimes', 'nullable', 'in:clash_create, clash_participate, clash_boost, user_certfied, ad'],
        'entity' => ['sometimes', 'nullable', 'in:clash, user'],
        'entity_id' => ['sometimes', 'nullable', 'integer'],
        'user_id' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'reference' => ['sometimes', 'nullable', 'string'],
        'provider_reference' => ['sometimes', 'nullable', 'string'],
        'order_number' => ['sometimes', 'nullable', 'string'],
        'amount' => ['sometimes', 'nullable', 'numeric'],
        'amount_customer' => ['sometimes', 'nullable', 'numeric'],
        'phone' => ['sometimes', 'nullable', 'string'],
        'currency' => ['sometimes', 'nullable', 'string'],
        'channel' => ['sometimes', 'nullable', 'string'],
        'type' => ['sometimes', 'integer'],
        'status' => ['sometimes', 'nullable', 'integer'],
        'reason' => ['sometimes', 'nullable', 'in:clash_create, clash_participate, clash_boost, user_certfied, ad'],
        'entity' => ['sometimes', 'nullable', 'in:clash, user'],
        'entity_id' => ['sometimes', 'nullable', 'integer'],
        'user_id' => ['sometimes', 'nullable', 'integer'],
    ];
}
