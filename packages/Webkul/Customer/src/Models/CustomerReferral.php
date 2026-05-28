<?php

namespace Webkul\Customer\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerReferral extends Model
{
    protected $fillable = ['referrer_id', 'code', 'referred_customer_id', 'order_placed', 'reward_issued'];

    public function referrer()
    {
        return $this->belongsTo(Customer::class, 'referrer_id');
    }

    public function referredCustomer()
    {
        return $this->belongsTo(Customer::class, 'referred_customer_id');
    }
}
