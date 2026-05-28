<?php

namespace Webkul\Loyalty\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLoyaltyTransaction extends Model
{
    protected $table = 'customer_loyalty_transactions';

    protected $fillable = ['customer_id', 'order_id', 'type', 'points', 'balance_after', 'description'];
}
