<?php

namespace Webkul\Affiliate\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateCommission extends Model
{
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
