<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['entity', 'entity_id', 'report_content', 'muted', 'for_user_id', 'reason_id', 'user_id'])]
class Report extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'entity_id' => 'integer',
            'muted' => 'boolean',
            'for_user_id' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'reason_id' => 'integer',
            'user_id' => 'integer',
        ];
    }

    public function reason(): BelongsTo
    {
        return $this->belongsTo(Reason::class, 'reason_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
