<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Translatable('level_name')]
#[Fillable(['level_name', 'min_score', 'max_score', 'icon', 'color', 'for_subject', 'created_by', 'updated_by', 'deleted_by'])]
class Level extends Model
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
            'min_score' => 'integer',
            'max_score' => 'integer',
            'for_subject' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
            'created_by' => 'integer',
            'updated_by' => 'integer',
            'deleted_by' => 'integer',
        ];
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class, 'level_id');
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class, 'level_id');
    }
}
