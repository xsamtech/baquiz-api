<?php

namespace App\Models\AI;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['tool_name', 'arguments', 'response', 'status', 'ai_message_id'])]
class AiToolCall extends Model
{
    protected $table = 'ai_tool_calls';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'arguments' => 'array',
            'response' => 'array',
        ];
    }

    public function aiMessage(): BelongsTo
    {
        return $this->belongsTo(AiMessage::class, 'ai_message_id');
    }
}
