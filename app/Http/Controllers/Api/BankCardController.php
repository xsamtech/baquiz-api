<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BankCardResource;
use App\Models\BankCard;

class BankCardController extends ApiController
{
    protected string $modelClass = BankCard::class;

    protected string $resourceClass = BankCardResource::class;

    protected string $messageKey = 'bank_cards';

    protected array $relationships = [
        'user',
    ];

    protected array $storeRules = [
        'card_name' => ['sometimes', 'nullable', 'string'],
        'card_number' => ['sometimes', 'nullable', 'string'],
        'expiration_date' => ['sometimes', 'nullable', 'string'],
        'cvv_code' => ['sometimes', 'nullable', 'string'],
        'provider' => ['sometimes', 'nullable', 'string'],
        'is_main' => ['sometimes', 'nullable', 'boolean'],
        'user_id' => ['required', 'integer'],
    ];

    protected array $updateRules = [
        'card_name' => ['sometimes', 'nullable', 'string'],
        'card_number' => ['sometimes', 'nullable', 'string'],
        'expiration_date' => ['sometimes', 'nullable', 'string'],
        'cvv_code' => ['sometimes', 'nullable', 'string'],
        'provider' => ['sometimes', 'nullable', 'string'],
        'is_main' => ['sometimes', 'nullable', 'boolean'],
        'user_id' => ['sometimes', 'integer'],
    ];
}
