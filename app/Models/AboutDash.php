<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Translatable('dash_content')]
#[Fillable(['dash_content', 'belongs_to', 'created_by', 'updated_by', 'deleted_by', 'about_content_id'])]
class AboutDash extends Model
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
            'belongs_to' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
            'created_by' => 'integer',
            'updated_by' => 'integer',
            'deleted_by' => 'integer',
            'about_content_id' => 'integer',
        ];
    }

    public function aboutContent(): BelongsTo
    {
        return $this->belongsTo(AboutContent::class, 'about_content_id');
    }
}
