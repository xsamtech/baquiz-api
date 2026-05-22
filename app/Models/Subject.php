<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['subject_name', 'subject_description', 'max_rating', 'weighting', 'status', 'level_id', 'clash_id'])]
class Subject extends Model
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
            'max_rating' => 'decimal:2',
            'weighting' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
            'level_id' => 'integer',
            'clash_id' => 'integer',
        ];
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function clash(): BelongsTo
    {
        return $this->belongsTo(Clash::class, 'clash_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'subject_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'subject_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'subject_user')
            ->withPivot('id', 'score', 'is_paid', 'created_by', 'updated_by')
            ->withTimestamps();
    }
}
