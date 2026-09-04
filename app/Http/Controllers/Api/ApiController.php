<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Resources\FileResource;
use App\Models\File;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

abstract class ApiController extends BaseController
{
    protected string $modelClass;

    protected string $resourceClass;

    protected string $messageKey;

    /** @var array<int, string> */
    protected array $relationships = [];

    /** @var array<string, array<int, string>> */
    protected array $storeRules = [];

    /** @var array<string, array<int, string>> */
    protected array $updateRules = [];

    public function index(): JsonResponse
    {
        $records = $this->modelClass::query()
            ->with($this->relationships)
            ->latest('id')
            ->paginate(10);

        return $this->handleResponse(
            $this->resourceClass::collection($records),
            Lang::get("api.{$this->messageKey}.index"),
            $records->lastPage(),
            $records->total()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->storeRules);
        $record = $this->modelClass::query()->create($data);
        $record->load($this->relationships);

        return $this->handleResponse(
            new $this->resourceClass($record),
            Lang::get("api.{$this->messageKey}.store")
        );
    }

    public function show(int $id): JsonResponse
    {
        $record = $this->findRecord($id);

        if (! $record) {
            return $this->handleError(null, Lang::get("api.{$this->messageKey}.not_found"), Response::HTTP_NOT_FOUND);
        }

        return $this->handleResponse(
            new $this->resourceClass($record),
            Lang::get("api.{$this->messageKey}.show")
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $record = $this->findRecord($id);

        if (! $record) {
            return $this->handleError(null, Lang::get("api.{$this->messageKey}.not_found"), Response::HTTP_NOT_FOUND);
        }

        $record->update($request->validate($this->updateRules));
        $record->load($this->relationships);

        return $this->handleResponse(
            new $this->resourceClass($record),
            Lang::get("api.{$this->messageKey}.update")
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $record = $this->findRecord($id);

        if (! $record) {
            return $this->handleError(null, Lang::get("api.{$this->messageKey}.not_found"), Response::HTTP_NOT_FOUND);
        }

        $record->delete();

        return $this->handleResponse(
            null,
            Lang::get("api.{$this->messageKey}.destroy")
        );
    }

    public function restore(int $id): JsonResponse
    {
        $record = $this->modelClass::withTrashed()->find($id);

        if (! $record) {
            return $this->handleError(null, Lang::get("api.{$this->messageKey}.not_found"), Response::HTTP_NOT_FOUND);
        }

        $record->restore();
        $record->load($this->relationships);

        return $this->handleResponse(
            new $this->resourceClass($record),
            Lang::has("api.{$this->messageKey}.restore")
                ? Lang::get("api.{$this->messageKey}.restore")
                : Lang::get('api.restored')
        );
    }

    public function addFiles(Request $request, int $id): JsonResponse
    {
        $record = $this->findRecord($id);

        if (! $record) {
            return $this->handleError(null, Lang::get("api.{$this->messageKey}.not_found"), Response::HTTP_NOT_FOUND);
        }

        $data = $request->validate([
            'files' => ['required', 'array', 'min:1'],
            'files.*.file_name' => ['sometimes', 'nullable', 'string'],
            'files.*.file_url' => ['required', 'string'],
            'files.*.file_description' => ['sometimes', 'nullable', 'string'],
            'files.*.file_type' => ['sometimes', 'nullable', 'in:video,photo,audio,document,id_card,ad,qr_code'],
        ]);

        $foreignKey = Str::singular($record->getTable()).'_id';
        $files = collect($data['files'])
            ->map(fn (array $file): File => File::query()->create([
                ...$file,
                'file_type' => $file['file_type'] ?? 'photo',
                $foreignKey => $record->getKey(),
            ]));

        return $this->handleResponse(
            FileResource::collection($files),
            Lang::get('api.files.store')
        );
    }

    /**
     * Send a payment request to FlexPay and persist its initial pending state.
     *
     * @param  array<string, mixed>  $attributes
     * @return array{payment: Payment, response: array<string, mixed>, successful: bool}
     *
     * @throws ValidationException
     */
    protected function initiateFlexPayPayment(array $attributes): array
    {
        $validated = Validator::validate($attributes, [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'type' => ['required', 'integer', Rule::in([1, 2])],
            'phone' => [
                Rule::requiredIf(($attributes['type'] ?? null) === 1),
                'nullable',
                'string',
                'regex:/^243\\d{9}$/',
            ],
            'amount' => ['required', 'numeric', 'gt:0'],
            'amount_customer' => ['sometimes', 'nullable', 'numeric', 'gt:0'],
            'currency' => ['required', 'string', Rule::in(['USD', 'CDF'])],
            'description' => [Rule::requiredIf(($attributes['type'] ?? null) === 2), 'nullable', 'string', 'max:500'],
            'callback_url' => ['sometimes', 'nullable', 'url'],
            'approve_url' => [Rule::requiredIf(($attributes['type'] ?? null) === 2), 'nullable', 'url'],
            'cancel_url' => [Rule::requiredIf(($attributes['type'] ?? null) === 2), 'nullable', 'url'],
            'decline_url' => [Rule::requiredIf(($attributes['type'] ?? null) === 2), 'nullable', 'url'],
            'channel' => ['sometimes', 'nullable', 'string', 'max:45'],
            'reason' => ['sometimes', 'nullable', Rule::in(['clash_create', 'clash_participate', 'clash_boost', 'user_certfied', 'ad'])],
            'entity' => ['sometimes', 'nullable', Rule::in(['clash', 'user'])],
            'entity_id' => ['sometimes', 'nullable', 'integer'],
        ]);

        $gateway = $validated['type'] === 1
            ? config('services.flexpay.gateway_mobile')
            : config('services.flexpay.gateway_card');
        $merchant = config('services.flexpay.merchant');
        $apiToken = config('services.flexpay.api_token');

        if (blank($gateway) || blank($merchant) || blank($apiToken)) {
            throw ValidationException::withMessages([
                'flexpay' => ['FlexPay must be configured before starting a payment.'],
            ]);
        }

        $reference = sprintf('REF-%08d-%d', random_int(0, 99_999_999), $validated['user_id']);
        $callbackUrl = $validated['callback_url'] ?? url("/api/payments/flexpay/{$reference}/callback");

        $payment = Payment::query()->create([
            'reference' => $reference,
            'amount' => $validated['amount'],
            'amount_customer' => $validated['amount_customer'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'currency' => $validated['currency'],
            'channel' => $validated['channel'] ?? ($validated['type'] === 1 ? 'mobile_money' : 'bank_card'),
            'type' => $validated['type'],
            'status' => 2,
            'reason' => $validated['reason'] ?? null,
            'entity' => $validated['entity'] ?? null,
            'entity_id' => $validated['entity_id'] ?? null,
            'user_id' => $validated['user_id'],
        ]);

        $payload = [
            'merchant' => $merchant,
            'type' => $validated['type'],
            'reference' => $reference,
            'amount' => $validated['amount'],
            'currency' => $validated['currency'],
        ];

        if (filled($validated['description'] ?? null)) {
            $payload['description'] = $validated['description'];
        }

        if ($validated['type'] === 1) {
            $payload['phone'] = $validated['phone'];
            $payload['callbackUrl'] = $callbackUrl;
        } else {
            $payload['callback_url'] = $callbackUrl;
            $payload['approve_url'] = $validated['approve_url'];
            $payload['cancel_url'] = $validated['cancel_url'];
            $payload['decline_url'] = $validated['decline_url'];
        }

        try {
            $response = Http::acceptJson()
                ->asJson()
                ->withToken($apiToken)
                ->connectTimeout(10)
                ->timeout(30)
                ->post($gateway, $payload);
        } catch (ConnectionException $exception) {
            $payment->update(['status' => 1]);

            return [
                'payment' => $payment->fresh(),
                'response' => [
                    'code' => '1',
                    'message' => $exception->getMessage(),
                ],
                'successful' => false,
            ];
        }

        $flexPayResponse = $response->json();
        $flexPayResponse = is_array($flexPayResponse) ? $flexPayResponse : [];
        $successful = $response->successful() && (string) ($flexPayResponse['code'] ?? '1') === '0';

        $payment->update([
            'provider_reference' => $flexPayResponse['provider_reference'] ?? null,
            'order_number' => $flexPayResponse['orderNumber'] ?? null,
            'status' => $successful ? 2 : 1,
        ]);

        return [
            'payment' => $payment->fresh(),
            'response' => $flexPayResponse,
            'successful' => $successful,
        ];
    }

    /**
     * Apply the result sent asynchronously by FlexPay to a payment.
     *
     * @param  array<string, mixed>  $payload
     */
    protected function handleFlexPayCallback(string $reference, array $payload): ?Payment
    {
        $payment = Payment::query()->where('reference', $reference)->first();

        if (! $payment) {
            return null;
        }

        $updates = [
            'provider_reference' => $payload['provider_reference'] ?? $payment->provider_reference,
            'order_number' => $payload['orderNumber'] ?? $payment->order_number,
        ];
        $status = $this->flexPayCallbackStatus($payload['status'] ?? null);

        if ($status !== null) {
            $updates['status'] = $status;
        }

        $payment->update($updates);

        return $payment->fresh();
    }

    protected function flexPayCallbackStatus(mixed $status): ?int
    {
        if (in_array($status, [0, 1, 2], true)) {
            return $status;
        }

        return match (Str::lower((string) $status)) {
            '0', 'success', 'successful', 'completed', 'approved' => 0,
            '1', 'failed', 'declined', 'cancelled', 'canceled', 'error' => 1,
            '2', 'pending', 'processing', 'in_progress' => 2,
            default => null,
        };
    }

    protected function findRecord(int $id): ?Model
    {
        return $this->modelClass::query()
            ->with($this->relationships)
            ->find($id);
    }
}
