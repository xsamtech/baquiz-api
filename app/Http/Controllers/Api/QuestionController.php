<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\HandlesSocialContent;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class QuestionController extends ApiController
{
    use HandlesSocialContent;

    protected string $modelClass = Question::class;

    protected string $resourceClass = QuestionResource::class;

    protected string $messageKey = 'questions';

    protected array $relationships = [
        'subject',
        'domain',
        'hashtags',
    ];

    protected array $storeRules = [
        'question_content' => ['sometimes', 'nullable', 'string'],
        'expected_time' => ['sometimes', 'nullable', 'integer'],
        'percentages_removed' => ['sometimes', 'nullable', 'numeric'],
        'max_rating' => ['sometimes', 'nullable', 'numeric'],
        'correct_assertions_count' => ['sometimes', 'nullable', 'integer'],
        'assertion_rating' => ['sometimes', 'nullable', 'numeric'],
        'assertions_combination_required' => ['sometimes', 'nullable', 'boolean'],
        'type' => ['sometimes', 'nullable', 'in:input_data,single_check,multiple_check'],
        'status' => ['sometimes', 'nullable', 'in:created,activated,disabled,blocked,deleted'],
        'subject_id' => ['sometimes', 'nullable', 'integer'],
        'domain_id' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'question_content' => ['sometimes', 'nullable', 'string'],
        'expected_time' => ['sometimes', 'nullable', 'integer'],
        'percentages_removed' => ['sometimes', 'nullable', 'numeric'],
        'max_rating' => ['sometimes', 'nullable', 'numeric'],
        'correct_assertions_count' => ['sometimes', 'nullable', 'integer'],
        'assertion_rating' => ['sometimes', 'nullable', 'numeric'],
        'assertions_combination_required' => ['sometimes', 'nullable', 'boolean'],
        'type' => ['sometimes', 'nullable', 'in:input_data,single_check,multiple_check'],
        'status' => ['sometimes', 'nullable', 'in:created,activated,disabled,blocked,deleted'],
        'subject_id' => ['sometimes', 'nullable', 'integer'],
        'domain_id' => ['sometimes', 'nullable', 'integer'],
    ];

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->storeRules);

        $question = DB::transaction(function () use ($data): Question {
            $question = Question::query()->create($data);
            $this->syncHashtags($question, 'question_content');
            $this->notifyMentionedUsers(null, $question->question_content, [
                'question_id' => $question->id,
            ]);

            return $question;
        });

        return $this->handleResponse(
            new QuestionResource($question->load($this->relationships)),
            Lang::get('api.questions.store')
        );
    }
}
