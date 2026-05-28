<?php

namespace Webkul\Wallet\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerWallet extends Model
{
    protected $fillable = ['customer_id', 'balance'];

    protected $casts = ['balance' => 'decimal:4'];

    public function transactions()
    {
        return $this->hasMany(CustomerWalletTransaction::class, 'customer_id', 'customer_id');
    }
}
