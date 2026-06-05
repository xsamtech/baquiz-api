<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PollchoiceResource;
use App\Models\Pollchoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class PollchoiceController extends ApiController
{
    protected string $modelClass = Pollchoice::class;

    protected string $resourceClass = PollchoiceResource::class;

    protected string $messageKey = 'pollchoices';

    protected array $relationships = [
        'message',
        'users',
    ];

    protected array $storeRules = [
        'choice_content' => ['sometimes', 'nullable', 'string'],
        'image_url' => ['sometimes', 'nullable', 'string'],
        'message_id' => ['required', 'integer'],
        'user_ids' => ['sometimes', 'array'],
        'user_ids.*' => ['integer'],
    ];

    protected array $updateRules = [
        'choice_content' => ['sometimes', 'nullable', 'string'],
        'image_url' => ['sometimes', 'nullable', 'string'],
        'message_id' => ['sometimes', 'integer'],
        'user_ids' => ['sometimes', 'array'],
        'user_ids.*' => ['integer'],
    ];

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->storeRules);

        $pollchoice = DB::transaction(function () use ($data): Pollchoice {
            $pollchoice = Pollchoice::query()->create(Arr::except($data, ['user_ids']));

            if (array_key_exists('user_ids', $data)) {
                $pollchoice->users()->sync($data['user_ids']);
            }

            return $pollchoice;
        });

        return $this->handleResponse(
            new PollchoiceResource($pollchoice->load($this->relationships)),
            Lang::get('api.pollchoices.store')
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $pollchoice = Pollchoice::query()->find($id);

        if (! $pollchoice) {
            return $this->handleError(null, Lang::get('api.pollchoices.not_found'));
        }

        $data = $request->validate($this->updateRules);

        DB::transaction(function () use ($pollchoice, $data): void {
            $pollchoice->update(Arr::except($data, ['user_ids']));

            if (array_key_exists('user_ids', $data)) {
                $pollchoice->users()->sync($data['user_ids']);
            }
        });

        return $this->handleResponse(
            new PollchoiceResource($pollchoice->load($this->relationships)),
            Lang::get('api.pollchoices.update')
        );
    }
}
