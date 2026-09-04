<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MedalUser extends Pivot
{
    protected $table = 'medal_user';

    public $incrementing = true;

    protected function casts(): array
    {
        return [
            'medal_id' => 'integer',
            'user_id' => 'integer',
            'clash_id' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function clash(): BelongsTo
    {
        return $this->belongsTo(Clash::class);
    }
}
