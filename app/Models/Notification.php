<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

#[Fillable(['uuid', 'type', 'is_read', 'from_user_id', 'to_user_id', 'clash_id', 'comment_id', 'message_id', 'question_id', 'assertion_id', 'answer_id'])]
class Notification extends Model
{
    use SoftDeletes;

    protected static function booted(): void
    {
        static::creating(function (self $notification): void {
            if (blank($notification->uuid)) {
                $notification->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
            'is_read' => 'boolean',
            'from_user_id' => 'integer',
            'to_user_id' => 'integer',
            'clash_id' => 'integer',
            'comment_id' => 'integer',
            'message_id' => 'integer',
            'question_id' => 'integer',
            'assertion_id' => 'integer',
            'answer_id' => 'integer',
        ];
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function clash(): BelongsTo
    {
        return $this->belongsTo(Clash::class, 'clash_id');
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'message_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function assertion(): BelongsTo
    {
        return $this->belongsTo(Assertion::class, 'assertion_id');
    }

    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class, 'answer_id');
    }
}
