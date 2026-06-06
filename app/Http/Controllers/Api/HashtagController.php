<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\HashtagResource;
use App\Models\Hashtag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;

class HashtagController extends ApiController
{
    protected string $modelClass = Hashtag::class;

    protected string $resourceClass = HashtagResource::class;

    protected string $messageKey = 'hashtags';

    protected array $relationships = [];

    protected array $storeRules = [
        'keyword' => ['required', 'string'],
    ];

    protected array $updateRules = [
        'keyword' => ['sometimes', 'string'],
    ];

    public function entities(string $hashtag): JsonResponse
    {
        $record = Hashtag::query()
            ->where('id', ctype_digit($hashtag) ? (int) $hashtag : 0)
            ->orWhere('keyword', $hashtag)
            ->with([
                'clashs',
                'comments',
                'messages.pollchoices.users',
                'questions',
                'assertions',
                'answers',
            ])
            ->first();

        if (! $record) {
            return $this->handleError(null, Lang::get('api.hashtags.not_found'), Response::HTTP_NOT_FOUND);
        }

        return $this->handleResponse(
            new HashtagResource($record),
            Lang::get('api.hashtags.show')
        );
    }
}
