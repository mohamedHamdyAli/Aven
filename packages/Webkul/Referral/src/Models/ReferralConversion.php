<?php

namespace Webkul\Referral\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralConversion extends Model
{
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
