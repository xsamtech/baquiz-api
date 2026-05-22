<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\FieldResource;
use App\Models\Field;

class FieldController extends ApiController
{
    protected string $modelClass = Field::class;

    protected string $resourceClass = FieldResource::class;

    protected string $messageKey = 'fields';

    protected array $relationships = [];

    protected array $storeRules = [
        'field_name' => ['required', 'array'],
        'field_description' => ['sometimes', 'nullable', 'array'],
        'icon' => ['sometimes', 'nullable', 'string'],
        'color' => ['sometimes', 'nullable', 'string'],
        'group' => ['sometimes', 'nullable', 'in:evaluation, vocational_guidance, survey'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'field_name' => ['sometimes', 'array'],
        'field_description' => ['sometimes', 'nullable', 'array'],
        'icon' => ['sometimes', 'nullable', 'string'],
        'color' => ['sometimes', 'nullable', 'string'],
        'group' => ['sometimes', 'nullable', 'in:evaluation, vocational_guidance, survey'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
    ];
}
