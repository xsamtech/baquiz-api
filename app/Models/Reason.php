<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Translatable('reason_content')]
#[Fillable(['uuid', 'reason_content', 'max_reports', 'entity', 'created_by', 'updated_by', 'deleted_by'])]
class Reason extends Model
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
            'max_reports' => 'integer',
        ];
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'reason_id');
    }
}
