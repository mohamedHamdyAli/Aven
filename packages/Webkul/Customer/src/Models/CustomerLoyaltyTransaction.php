<?php

namespace Webkul\Customer\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLoyaltyTransaction extends Model
{
    protected $fillable = ['customer_id', 'points', 'type', 'description', 'order_id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
