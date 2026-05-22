<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Translatable('field_name', 'field_description')]
#[Fillable(['field_name', 'field_description', 'icon', 'color', 'group', 'created_by', 'updated_by', 'deleted_by'])]
class Field extends Model
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

    public function clashs(): HasMany
    {
        return $this->hasMany(Clash::class, 'field_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'field_id');
    }
}
