<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;

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

    public function flexPayCallback(Request $request, string $reference): JsonResponse
    {
        $payment = $this->handleFlexPayCallback($reference, $request->all());

        if (! $payment) {
            return $this->handleError(null, Lang::get('api.payments.not_found'), Response::HTTP_NOT_FOUND);
        }

        return $this->handleResponse(new PaymentResource($payment), 'FlexPay callback received.');
    }
}
