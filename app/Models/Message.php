<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['message_content', 'answered_for', 'status', 'user_id', 'addressee_user_id', 'addressee_circle_id'])]
class Message extends Model
{
    use SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'answered_for' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
            'user_id' => 'integer',
            'addressee_user_id' => 'integer',
            'addressee_circle_id' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function addresseeUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'addressee_user_id');
    }

    public function addresseeCircle(): BelongsTo
    {
        return $this->belongsTo(Circle::class, 'addressee_circle_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'message_id');
    }

    public function hashtags(): BelongsToMany
    {
        return $this->belongsToMany(Hashtag::class, 'hashtag_message')
            ->withPivot('id')
            ->withTimestamps();
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'message_id');
    }
}
