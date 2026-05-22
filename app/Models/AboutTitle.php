<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Translatable('title')]
#[Fillable(['title', 'alias', 'created_by', 'updated_by', 'deleted_by', 'about_subject_id'])]
class AboutTitle extends Model
{
    use HasTranslations, SoftDeletes;

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
            'created_by' => 'integer',
            'updated_by' => 'integer',
            'deleted_by' => 'integer',
            'about_subject_id' => 'integer',
        ];
    }

    public function aboutSubject(): BelongsTo
    {
        return $this->belongsTo(AboutSubject::class, 'about_subject_id');
    }

    public function blockedUsers(): HasMany
    {
        return $this->hasMany(BlockedUser::class, 'about_title_id');
    }

    public function aboutContents(): HasMany
    {
        return $this->hasMany(AboutContent::class, 'about_title_id');
    }
}
