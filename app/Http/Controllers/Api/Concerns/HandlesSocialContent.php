<?php

namespace App\Http\Controllers\Api\Concerns;

use App\Models\Hashtag;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait HandlesSocialContent
{
    protected function syncHashtags(Model $model, string $contentColumn): void
    {
        if (! method_exists($model, 'hashtags')) {
            return;
        }

        $hashtags = collect(getHashtags($model->{$contentColumn} ?? ''))
            ->map(fn (string $keyword): int => Hashtag::query()->firstOrCreate(['keyword' => $keyword])->id)
            ->unique()
            ->values();

        if ($hashtags->isNotEmpty()) {
            $model->hashtags()->syncWithoutDetaching($hashtags);
        }
    }

    /**
     * @param  array<string, int|null>  $foreignKeys
     */
    protected function notifyMentionedUsers(?int $fromUserId, ?string $content, array $foreignKeys): void
    {
        $this->mentionedUsers($content)
            ->reject(fn (User $user): bool => $fromUserId !== null && $user->id === $fromUserId)
            ->each(fn (User $user): Notification => Notification::query()->create([
                'type' => 'user_mention',
                'from_user_id' => $fromUserId,
                'to_user_id' => $user->id,
                ...$foreignKeys,
            ]));
    }

    /**
     * @return Collection<int, User>
     */
    protected function mentionedUsers(?string $content): Collection
    {
        return collect(getMentions($content ?? ''))
            ->unique()
            ->flatMap(function (string $mention): Collection {
                return User::query()
                    ->where('id', ctype_digit($mention) ? (int) $mention : 0)
                    ->orWhere('username', $mention)
                    ->get();
            })
            ->unique('id')
            ->values();
    }
}
