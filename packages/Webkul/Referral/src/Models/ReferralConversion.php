<?php

namespace Webkul\Referral\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralConversion extends Model
{
    use HasFactory;

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Webkul\Referral\Database\Factories\ReferralConversionFactory::new();
    }

    protected $fillable = [
        'referral_code',
        'referrer_customer_id',
        'referred_customer_id',
        'referred_email',
        'order_id',
        'status',
        'rewarded_at',
    ];

    protected $casts = [
        'rewarded_at' => 'datetime',
    ];
}
