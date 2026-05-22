<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\RoleResource;
use App\Models\Role;

class RoleController extends ApiController
{
    protected string $modelClass = Role::class;

    protected string $resourceClass = RoleResource::class;

    protected string $messageKey = 'roles';

    protected array $relationships = [];

    protected array $storeRules = [
        'role_name' => ['required', 'array'],
        'role_description' => ['sometimes', 'nullable', 'array'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'role_name' => ['sometimes', 'array'],
        'role_description' => ['sometimes', 'nullable', 'array'],
        'created_by' => ['sometimes', 'nullable', 'integer'],
        'updated_by' => ['sometimes', 'nullable', 'integer'],
        'deleted_by' => ['sometimes', 'nullable', 'integer'],
    ];
}
