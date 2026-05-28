<?php

namespace Webkul\CartRule\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\CartRule\Contracts\CartRuleCouponAssignment as CartRuleCouponAssignmentContract;

class CartRuleCouponAssignment extends Model implements CartRuleCouponAssignmentContract
{
    protected $fillable = ['cart_rule_coupon_id', 'phone'];

    public function coupon()
    {
        return $this->belongsTo(CartRuleCoupon::class, 'cart_rule_coupon_id');
    }
}
