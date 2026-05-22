<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\HandlesSocialContent;
use App\Http\Resources\AnswerResource;
use App\Models\Answer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class AnswerController extends ApiController
{
    use HandlesSocialContent;

    protected string $modelClass = Answer::class;

    protected string $resourceClass = AnswerResource::class;

    protected string $messageKey = 'answers';

    protected array $relationships = [
        'question',
        'hashtags',
        'user',
    ];

    protected array $storeRules = [
        'answer_content' => ['sometimes', 'nullable', 'string'],
        'time_taken' => ['sometimes', 'nullable', 'integer'],
        'question_id' => ['required', 'integer'],
        'user_id' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'answer_content' => ['sometimes', 'nullable', 'string'],
        'time_taken' => ['sometimes', 'nullable', 'integer'],
        'question_id' => ['sometimes', 'integer'],
        'user_id' => ['sometimes', 'nullable', 'integer'],
    ];

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->storeRules);

        $answer = DB::transaction(function () use ($data): Answer {
            $answer = Answer::query()->create($data);
            $this->syncHashtags($answer, 'answer_content');
            $this->notifyMentionedUsers($answer->user_id, $answer->answer_content, [
                'answer_id' => $answer->id,
                'question_id' => $answer->question_id,
            ]);

            return $answer;
        });

        return $this->handleResponse(
            new AnswerResource($answer->load($this->relationships)),
            Lang::get('api.answers.store')
        );
    }
}
