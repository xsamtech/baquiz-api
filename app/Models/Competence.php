<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Translatable('competence_name', 'competence_description')]
#[Fillable(['competence_name', 'competence_description', 'created_by', 'updated_by', 'deleted_by'])]
class Competence extends Model
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
        ];
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class, 'competence_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'competence_user')
            ->withPivot('id', 'score', 'created_by', 'updated_by', 'domain_id')
            ->withTimestamps();
    }
}
