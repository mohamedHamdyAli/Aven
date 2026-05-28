<?php

namespace Webkul\Wallet\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerWalletTransaction extends Model
{
    protected $fillable = ['customer_id', 'order_id', 'type', 'amount', 'balance_after', 'note'];

    protected $casts = ['amount' => 'decimal:4', 'balance_after' => 'decimal:4'];
}
