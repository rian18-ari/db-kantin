<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rfid_card_id',
        'order_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'status',
        'payment_method',
        'midtrans_payload',
        'notes',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'balance_before' => 'decimal:2',
            'balance_after' => 'decimal:2',
            'midtrans_payload' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns this transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the RFID card associated with this transaction (nullable for topup).
     */
    public function rfidCard(): BelongsTo
    {
        return $this->belongsTo(RfidCard::class);
    }

    /**
     * Scope: filter by type (topup | payment).
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: successful transactions only.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }
}
