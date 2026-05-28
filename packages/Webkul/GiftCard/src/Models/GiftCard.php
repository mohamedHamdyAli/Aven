<?php

namespace Webkul\GiftCard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
{
    use HasFactory;

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Webkul\GiftCard\Database\Factories\GiftCardFactory::new();
    }

    protected $fillable = [
        'code', 'initial_balance', 'used_amount', 'is_active',
        'recipient_email', 'recipient_name', 'expires_at', 'message',
    ];

    protected $casts = [
        'initial_balance' => 'float',
        'used_amount'     => 'float',
        'is_active'       => 'boolean',
        'expires_at'      => 'date',
    ];

    public function getRemainingBalanceAttribute(): float
    {
        return max(0, $this->initial_balance - $this->used_amount);
    }

    public function isUsable(): bool
    {
        return $this->is_active
            && $this->remaining_balance > 0
            && (! $this->expires_at || $this->expires_at->isFuture());
    }

    public static function generateCode(): string
    {
        do {
            $code = implode('-', [
                strtoupper(substr(md5(uniqid()), 0, 4)),
                strtoupper(substr(md5(uniqid()), 4, 4)),
                strtoupper(substr(md5(uniqid()), 8, 4)),
            ]);
        } while (static::where('code', $code)->exists());

        return $code;
    }
}
