<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\HandlesSocialContent;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class CommentController extends ApiController
{
    use HandlesSocialContent;

    protected string $modelClass = Comment::class;

    protected string $resourceClass = CommentResource::class;

    protected string $messageKey = 'comments';

    protected array $relationships = [
        'clash',
        'hashtags',
        'user',
    ];

    protected array $storeRules = [
        'comment_content' => ['sometimes', 'nullable', 'string'],
        'answered_for' => ['sometimes', 'nullable', 'integer'],
        'clash_id' => ['required', 'integer'],
        'user_id' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'comment_content' => ['sometimes', 'nullable', 'string'],
        'answered_for' => ['sometimes', 'nullable', 'integer'],
        'clash_id' => ['sometimes', 'integer'],
        'user_id' => ['sometimes', 'nullable', 'integer'],
    ];

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->storeRules);

        $comment = DB::transaction(function () use ($data): Comment {
            $comment = Comment::query()->create($data);
            $this->syncHashtags($comment, 'comment_content');
            $this->notifyMentionedUsers($comment->user_id, $comment->comment_content, [
                'comment_id' => $comment->id,
                'clash_id' => $comment->clash_id,
            ]);

            return $comment;
        });

        return $this->handleResponse(
            new CommentResource($comment->load($this->relationships)),
            Lang::get('api.comments.store')
        );
    }
}
