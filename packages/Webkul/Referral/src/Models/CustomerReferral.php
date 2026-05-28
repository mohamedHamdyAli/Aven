<?php

namespace Webkul\Referral\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerReferral extends Model
{
    use HasFactory;

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Webkul\Referral\Database\Factories\CustomerReferralFactory::new();
    }

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
