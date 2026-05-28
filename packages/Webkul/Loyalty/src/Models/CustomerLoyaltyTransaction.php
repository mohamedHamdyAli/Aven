<?php

namespace Webkul\Loyalty\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerLoyaltyTransaction extends Model
{
    use HasFactory;

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Webkul\Loyalty\Database\Factories\CustomerLoyaltyTransactionFactory::new();
    }

    protected $table = 'customer_loyalty_transactions';

    protected $fillable = ['customer_id', 'order_id', 'type', 'points', 'balance_after', 'description'];
}
