<?php

namespace Webkul\Wallet\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerWallet extends Model
{
    use HasFactory;

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Webkul\Wallet\Database\Factories\CustomerWalletFactory::new();
    }

    protected $fillable = ['customer_id', 'balance'];

    protected $casts = ['balance' => 'decimal:4'];

    public function transactions()
    {
        return $this->hasMany(CustomerWalletTransaction::class, 'customer_id', 'customer_id');
    }
}
