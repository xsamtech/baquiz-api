<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Translatable('subtitle', 'content')]
#[Fillable(['subtitle', 'content', 'created_by', 'updated_by', 'deleted_by', 'about_title_id'])]
class AboutContent extends Model
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
            'about_title_id' => 'integer',
        ];
    }

    public function aboutTitle(): BelongsTo
    {
        return $this->belongsTo(AboutTitle::class, 'about_title_id');
    }

    public function aboutDashes(): HasMany
    {
        return $this->hasMany(AboutDash::class, 'about_content_id');
    }
}
