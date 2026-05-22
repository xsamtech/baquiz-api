<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\FileResource;
use App\Models\File;

class FileController extends ApiController
{
    protected string $modelClass = File::class;

    protected string $resourceClass = FileResource::class;

    protected string $messageKey = 'files';

    protected array $relationships = [
        'question',
        'assertion',
        'answer',
        'clash',
        'user',
        'subject',
        'field',
        'comment',
        'domain',
        'message',
    ];

    protected array $storeRules = [
        'file_name' => ['sometimes', 'nullable', 'string'],
        'file_url' => ['required', 'string'],
        'file_description' => ['sometimes', 'nullable', 'string'],
        'file_type' => ['sometimes', 'nullable', 'in:video, photo, audio, document, id_card, ad, qr_code'],
        'question_id' => ['sometimes', 'nullable', 'integer'],
        'assertion_id' => ['sometimes', 'nullable', 'integer'],
        'answer_id' => ['sometimes', 'nullable', 'integer'],
        'clash_id' => ['sometimes', 'nullable', 'integer'],
        'user_id' => ['sometimes', 'nullable', 'integer'],
        'subject_id' => ['sometimes', 'nullable', 'integer'],
        'field_id' => ['sometimes', 'nullable', 'integer'],
        'comment_id' => ['sometimes', 'nullable', 'integer'],
        'domain_id' => ['sometimes', 'nullable', 'integer'],
        'message_id' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'file_name' => ['sometimes', 'nullable', 'string'],
        'file_url' => ['sometimes', 'string'],
        'file_description' => ['sometimes', 'nullable', 'string'],
        'file_type' => ['sometimes', 'nullable', 'in:video, photo, audio, document, id_card, ad, qr_code'],
        'question_id' => ['sometimes', 'nullable', 'integer'],
        'assertion_id' => ['sometimes', 'nullable', 'integer'],
        'answer_id' => ['sometimes', 'nullable', 'integer'],
        'clash_id' => ['sometimes', 'nullable', 'integer'],
        'user_id' => ['sometimes', 'nullable', 'integer'],
        'subject_id' => ['sometimes', 'nullable', 'integer'],
        'field_id' => ['sometimes', 'nullable', 'integer'],
        'comment_id' => ['sometimes', 'nullable', 'integer'],
        'domain_id' => ['sometimes', 'nullable', 'integer'],
        'message_id' => ['sometimes', 'nullable', 'integer'],
    ];
}
