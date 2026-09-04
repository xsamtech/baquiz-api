<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['uuid', 'clash_code', 'clash_description', 'start_at', 'end_at', 'price', 'currency', 'is_competition', 'type', 'last_boost_at', 'boost_type', 'field_id', 'user_id'])]
class Clash extends Model
{
    use SoftDeletes;

    protected $table = 'clashs';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'price' => 'decimal:2',
            'is_competition' => 'boolean',
            'last_boost_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
            'field_id' => 'integer',
            'user_id' => 'integer',
        ];
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class, 'field_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class, 'clash_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'clash_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'clash_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'clash_id');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'clash_user', 'clash_id', 'user_id')
            ->withPivot('id', 'participated', 'reaction')
            ->withTimestamps();
    }

    public function hashtags(): BelongsToMany
    {
        return $this->belongsToMany(Hashtag::class, 'hashtag_clash', 'clash_id', 'hashtag_id')
            ->withPivot('id')
            ->withTimestamps();
    }

    public function medals(): BelongsToMany
    {
        return $this->belongsToMany(Medal::class, 'medal_user')
            ->using(MedalUser::class)
            ->withPivot('id', 'user_id')
            ->withTimestamps();
    }
}
