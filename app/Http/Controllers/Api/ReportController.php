<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ReportResource;
use App\Models\Report;

class ReportController extends ApiController
{
    protected string $modelClass = Report::class;

    protected string $resourceClass = ReportResource::class;

    protected string $messageKey = 'reports';

    protected array $relationships = [
        'reason',
        'user',
    ];

    protected array $storeRules = [
        'entity' => ['sometimes', 'nullable', 'in:clash, course, subject, question, user'],
        'entity_id' => ['sometimes', 'nullable', 'integer'],
        'report_content' => ['sometimes', 'nullable', 'string'],
        'muted' => ['sometimes', 'nullable', 'boolean'],
        'for_user_id' => ['sometimes', 'nullable', 'integer'],
        'reason_id' => ['sometimes', 'nullable', 'integer'],
        'user_id' => ['required', 'integer'],
    ];

    protected array $updateRules = [
        'entity' => ['sometimes', 'nullable', 'in:clash, course, subject, question, user'],
        'entity_id' => ['sometimes', 'nullable', 'integer'],
        'report_content' => ['sometimes', 'nullable', 'string'],
        'muted' => ['sometimes', 'nullable', 'boolean'],
        'for_user_id' => ['sometimes', 'nullable', 'integer'],
        'reason_id' => ['sometimes', 'nullable', 'integer'],
        'user_id' => ['sometimes', 'integer'],
    ];
}
