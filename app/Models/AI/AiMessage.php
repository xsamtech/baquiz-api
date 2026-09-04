<?php

namespace App\Models\AI;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['role', 'content', 'model', 'prompt_tokens', 'completion_tokens', 'total_tokens', 'response_time_ms', 'error_message', 'conversation_id'])]
class AiMessage extends Model
{
    protected $table = 'ai_messages';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'prompt_tokens' => 'integer',
            'completion_tokens' => 'integer',
            'total_tokens' => 'integer',
            'response_time_ms' => 'integer',
        ];
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(AiConversation::class, 'conversation_id');
    }

    public function toolCalls(): HasMany
    {
        return $this->hasMany(AiToolCall::class, 'ai_message_id');
    }
}
