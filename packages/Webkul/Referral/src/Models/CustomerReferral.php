<?php

namespace Webkul\Referral\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerReferral extends Model
{
    protected $fillable = [
        'customer_id',
        'referral_code',
        'times_used',
        'total_earned',
    ];

    protected $casts = [
        'total_earned' => 'decimal:4',
        'times_used'   => 'integer',
    ];
}
