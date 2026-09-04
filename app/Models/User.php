<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

#[Fillable(['uuid', 'name', 'firstname', 'lastname', 'surname', 'organization_name', 'about', 'gender', 'birthdate', 'country', 'city', 'address_1', 'address_2', 'p_o_box', 'currency', 'email', 'phone', 'email_verified_at', 'phone_verfied_at', 'username', 'password', 'api_token', 'api_key', 'avatar_url', 'cover_url', 'promo_code', 'two_factor_secret', 'two_factor_recovery_codes', 'two_factor_email_confirmed_at', 'two_factor_phone_confirmed_at', 'tips_at_every_login', 'is_online', 'status'])]
#[Hidden(['password', 'remember_token', 'api_token', 'api_key', 'two_factor_secret', 'two_factor_recovery_codes'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected static function booted(): void
    {
        static::creating(function (self $user): void {
            if (blank($user->uuid)) {
                $user->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
            'email_verified_at' => 'datetime',
            'phone_verfied_at' => 'datetime',
            'two_factor_email_confirmed_at' => 'datetime',
            'two_factor_phone_confirmed_at' => 'datetime',
            'tips_at_every_login' => 'boolean',
            'is_online' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user')
            ->withPivot('id', 'is_selected')
            ->withTimestamps();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class, 'user_id');
    }

    public function clashs(): HasMany
    {
        return $this->hasMany(Clash::class, 'user_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function circles(): HasMany
    {
        return $this->hasMany(Circle::class, 'user_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'user_id');
    }

    public function messagesByAddresseeUser(): HasMany
    {
        return $this->hasMany(Message::class, 'addressee_user_id');
    }

    public function pollchoices(): BelongsToMany
    {
        return $this->belongsToMany(Pollchoice::class, 'pollchoice_user')
            ->withPivot('id')
            ->withTimestamps();
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'user_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'from_user_id');
    }

    public function notificationsByToUser(): HasMany
    {
        return $this->hasMany(Notification::class, 'to_user_id');
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subject_user')
            ->withPivot('id', 'score', 'is_paid', 'created_by', 'updated_by')
            ->withTimestamps();
    }

    public function promoCodes(): HasMany
    {
        return $this->hasMany(PromoCode::class, 'user_id');
    }

    public function blockedUsers(): HasMany
    {
        return $this->hasMany(BlockedUser::class, 'user_id');
    }

    public function clashsAsParticipant(): BelongsToMany
    {
        return $this->belongsToMany(Clash::class, 'clash_user', 'user_id', 'clash_id')
            ->withPivot('id', 'participated', 'reaction')
            ->withTimestamps();
    }

    public function medals(): BelongsToMany
    {
        return $this->belongsToMany(Medal::class, 'medal_user')
            ->using(MedalUser::class)
            ->withPivot('id', 'clash_id')
            ->withTimestamps();
    }

    public function histories(): HasMany
    {
        return $this->hasMany(History::class, 'user_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'user_id');
    }

    public function subscriptionsByFollower(): HasMany
    {
        return $this->hasMany(Subscription::class, 'follower_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'user_id');
    }

    public function competences(): BelongsToMany
    {
        return $this->belongsToMany(Competence::class, 'competence_user')
            ->withPivot('id', 'score', 'created_by', 'updated_by', 'domain_id')
            ->withTimestamps();
    }

    public function circlesAsMember(): BelongsToMany
    {
        return $this->belongsToMany(Circle::class, 'circle_user')
            ->withPivot('id', 'is_admin')
            ->withTimestamps();
    }

    public function accountSwitches(): HasMany
    {
        return $this->hasMany(AccountSwitch::class, 'from_user_id');
    }

    public function accountSwitchesByToUser(): HasMany
    {
        return $this->hasMany(AccountSwitch::class, 'to_user_id');
    }

    public function websites(): HasMany
    {
        return $this->hasMany(Website::class);
    }

    public function aiConversations(): HasMany
    {
        return $this->hasMany(AI\AiConversation::class);
    }
}
