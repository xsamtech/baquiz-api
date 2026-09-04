<?php

namespace App\Models\AI;

use App\Models\File;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['file_id'])]
class AiMessageFile extends Model
{
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
