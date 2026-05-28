<?php

namespace Webkul\Wallet\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerWalletTransaction extends Model
{
    use HasFactory;

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Webkul\Wallet\Database\Factories\CustomerWalletTransactionFactory::new();
    }

    protected $fillable = ['customer_id', 'order_id', 'type', 'amount', 'balance_after', 'note'];

    protected $casts = ['amount' => 'decimal:4', 'balance_after' => 'decimal:4'];
}
