<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\CartRule\Models\CartRuleCoupon;

it('allows mass assignment of fillable fields', function () {
    $coupon = new CartRuleCoupon([
        'code'               => 'SUMMER10',
        'usage_limit'        => 100,
        'usage_per_customer' => 1,
        'times_used'         => 0,
        'type'               => 0,
        'cart_rule_id'       => null,
        'is_primary'         => 1,
    ]);

    expect($coupon->code)->toBe('SUMMER10')
        ->and($coupon->usage_limit)->toBe(100);
});

it('has a cart_rule relationship', function () {
    expect((new CartRuleCoupon)->cart_rule())->toBeInstanceOf(BelongsTo::class);
});

it('has many coupon_usage relationship', function () {
    expect((new CartRuleCoupon)->coupon_usage())->toBeInstanceOf(HasMany::class);
});

it('builds a model via factory make', function () {
    $coupon = CartRuleCoupon::factory()->make();

    expect((string) $coupon->code)->toBeString()
        ->and($coupon->usage_limit)->toBe(100);
});
