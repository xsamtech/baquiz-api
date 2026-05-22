<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\HandlesSocialContent;
use App\Http\Resources\AssertionResource;
use App\Models\Assertion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class AssertionController extends ApiController
{
    use HandlesSocialContent;

    protected string $modelClass = Assertion::class;

    protected string $resourceClass = AssertionResource::class;

    protected string $messageKey = 'assertions';

    protected array $relationships = [
        'question',
        'hashtags',
    ];

    protected array $storeRules = [
        'assertion_content' => ['required', 'string'],
        'is_real_answer' => ['sometimes', 'nullable', 'boolean'],
        'question_id' => ['required', 'integer'],
    ];

    protected array $updateRules = [
        'assertion_content' => ['sometimes', 'string'],
        'is_real_answer' => ['sometimes', 'nullable', 'boolean'],
        'question_id' => ['sometimes', 'integer'],
    ];

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->storeRules);

        $assertion = DB::transaction(function () use ($data): Assertion {
            $assertion = Assertion::query()->create($data);
            $this->syncHashtags($assertion, 'assertion_content');
            $this->notifyMentionedUsers(null, $assertion->assertion_content, [
                'assertion_id' => $assertion->id,
                'question_id' => $assertion->question_id,
            ]);

            return $assertion;
        });

        return $this->handleResponse(
            new AssertionResource($assertion->load($this->relationships)),
            Lang::get('api.assertions.store')
        );
    }
}
