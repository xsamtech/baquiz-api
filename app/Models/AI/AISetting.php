<?php

namespace App\Models\AI;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['provider', 'model', 'temperature', 'max_tokens', 'stream', 'enabled'])]
class AISetting extends Model
{
    protected $table = 'ai_settings';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'temperature' => 'float',
            'stream' => 'boolean',
            'enabled' => 'boolean',
            'max_tokens' => 'integer',
        ];
    }
}
