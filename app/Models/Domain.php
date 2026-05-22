<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Translatable('domain_name', 'domain_description')]
#[Fillable(['domain_name', 'domain_description', 'created_by', 'updated_by', 'deleted_by'])]
class Domain extends Model
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

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'domain_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'domain_id');
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class, 'domain_id');
    }

    public function competences(): BelongsToMany
    {
        return $this->belongsToMany(Competence::class, 'competence_user', 'domain_id', 'competence_id')
            ->withPivot('id', 'user_id', 'score', 'created_by', 'updated_by')
            ->withTimestamps();
    }
}
