<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['reference', 'provider_reference', 'order_number', 'amount', 'amount_customer', 'phone', 'currency', 'channel', 'type', 'status', 'reason', 'entity', 'entity_id', 'user_id'])]
class Payment extends Model
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
            'amount' => 'decimal:2',
            'amount_customer' => 'decimal:2',
            'type' => 'integer',
            'status' => 'integer',
            'entity_id' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
            'user_id' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function moneyTransfers(): HasMany
    {
        return $this->hasMany(MoneyTransfer::class, 'payment_id');
    }
}
