<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['website_name', 'website_url', 'user_id'])]
class Website extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
