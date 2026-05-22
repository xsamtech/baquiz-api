<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ReasonResource;
use App\Models\Reason;

class ReasonController extends ApiController
{
    protected string $modelClass = Reason::class;

    protected string $resourceClass = ReasonResource::class;

    protected string $messageKey = 'reasons';

    protected array $relationships = [];

    protected array $storeRules = [
        'reason_content' => ['required', 'array'],
        'entity' => ['required', 'in:clash, course, subject, question, user'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'reason_content' => ['sometimes', 'array'],
        'entity' => ['sometimes', 'in:clash, course, subject, question, user'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
    ];
}
