<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['file_name', 'file_url', 'file_description', 'file_type', 'question_id', 'assertion_id', 'answer_id', 'clash_id', 'user_id', 'subject_id', 'field_id', 'comment_id', 'domain_id', 'message_id'])]
class File extends Model
{
    use SoftDeletes;

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
            'question_id' => 'integer',
            'assertion_id' => 'integer',
            'answer_id' => 'integer',
            'clash_id' => 'integer',
            'user_id' => 'integer',
            'subject_id' => 'integer',
            'field_id' => 'integer',
            'comment_id' => 'integer',
            'domain_id' => 'integer',
            'message_id' => 'integer',
        ];
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

    public function clash(): BelongsTo
    {
        return $this->belongsTo(Clash::class, 'clash_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class, 'field_id');
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class, 'domain_id');
    }

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'message_id');
    }
}
