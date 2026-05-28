<?php

namespace Webkul\Affiliate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateCommission extends Model
{
    use HasFactory;

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Webkul\Affiliate\Database\Factories\AffiliateCommissionFactory::new();
    }

    protected $fillable = ['affiliate_id', 'order_id', 'order_total', 'commission', 'status'];

    protected $casts = [
        'order_total' => 'decimal:4',
        'commission'  => 'decimal:4',
    ];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }
}
