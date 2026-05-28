<?php

namespace Webkul\Loyalty\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLoyaltyPoints extends Model
{
    protected $table = 'customer_loyalty_points';

    protected $fillable = ['customer_id', 'balance'];

    public function transactions()
    {
        return $this->hasMany(CustomerLoyaltyTransaction::class, 'customer_id', 'customer_id');
    }
}
