<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\MoneyTransferResource;
use App\Models\MoneyTransfer;

class MoneyTransferController extends ApiController
{
    protected string $modelClass = MoneyTransfer::class;

    protected string $resourceClass = MoneyTransferResource::class;

    protected string $messageKey = 'money_transfers';

    protected array $relationships = [
        'payment',
    ];

    protected array $storeRules = [
        'has_commission' => ['sometimes', 'nullable', 'boolean'],
        'commission_amount' => ['sometimes', 'nullable', 'numeric'],
        'status' => ['sometimes', 'nullable', 'in:done, failed'],
        'payment_id' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'has_commission' => ['sometimes', 'nullable', 'boolean'],
        'commission_amount' => ['sometimes', 'nullable', 'numeric'],
        'status' => ['sometimes', 'nullable', 'in:done, failed'],
        'payment_id' => ['sometimes', 'nullable', 'integer'],
    ];
}
