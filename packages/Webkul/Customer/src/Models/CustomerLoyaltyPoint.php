<?php

namespace Webkul\Customer\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLoyaltyPoint extends Model
{
    protected $fillable = ['customer_id', 'balance'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function transactions()
    {
        return $this->hasMany(CustomerLoyaltyTransaction::class, 'customer_id', 'customer_id');
    }
}
