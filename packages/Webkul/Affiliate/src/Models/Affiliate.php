<?php

namespace Webkul\Affiliate\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Affiliate extends Model
{
    protected $fillable = [
        'customer_id', 'name', 'email', 'code',
        'status', 'commission_rate', 'total_earned', 'total_paid', 'notes',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'total_earned'    => 'decimal:4',
        'total_paid'      => 'decimal:4',
    ];

    public static function generateCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (static::where('code', $code)->exists());

        return $code;
    }

    public function commissions()
    {
        return $this->hasMany(AffiliateCommission::class);
    }

    public function clicks()
    {
        return $this->hasMany(AffiliateClick::class);
    }

    public function pendingBalance(): float
    {
        return (float) $this->commissions()
            ->where('status', 'approved')
            ->sum('commission');
    }
}
