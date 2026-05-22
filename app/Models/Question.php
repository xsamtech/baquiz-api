<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['question_content', 'expected_time', 'percentages_removed', 'max_rating', 'correct_assertions_count', 'assertion_rating', 'assertions_combination_required', 'type', 'status', 'subject_id', 'domain_id'])]
class Question extends Model
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
            'expected_time' => 'integer',
            'percentages_removed' => 'decimal:2',
            'max_rating' => 'decimal:2',
            'correct_assertions_count' => 'integer',
            'assertion_rating' => 'decimal:2',
            'assertions_combination_required' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
            'subject_id' => 'integer',
            'domain_id' => 'integer',
        ];
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class, 'domain_id');
    }

    public function assertions(): HasMany
    {
        return $this->hasMany(Assertion::class, 'question_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'question_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'question_id');
    }

    public function hashtags(): BelongsToMany
    {
        return $this->belongsToMany(Hashtag::class, 'hashtag_question')
            ->withPivot('id')
            ->withTimestamps();
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'question_id');
    }
}
