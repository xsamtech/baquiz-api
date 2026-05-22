<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['keyword'])]
class Hashtag extends Model
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
        ];
    }

    public function clashs(): BelongsToMany
    {
        return $this->belongsToMany(Clash::class, 'hashtag_clash', 'hashtag_id', 'clash_id')
            ->withPivot('id')
            ->withTimestamps();
    }

    public function comments(): BelongsToMany
    {
        return $this->belongsToMany(Comment::class, 'hashtag_comment')
            ->withPivot('id')
            ->withTimestamps();
    }

    public function messages(): BelongsToMany
    {
        return $this->belongsToMany(Message::class, 'hashtag_message')
            ->withPivot('id')
            ->withTimestamps();
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'hashtag_question')
            ->withPivot('id')
            ->withTimestamps();
    }

    public function assertions(): BelongsToMany
    {
        return $this->belongsToMany(Assertion::class, 'hashtag_assertion')
            ->withPivot('id')
            ->withTimestamps();
    }

    public function answers(): BelongsToMany
    {
        return $this->belongsToMany(Answer::class, 'hashtag_answer')
            ->withPivot('id')
            ->withTimestamps();
    }
}
