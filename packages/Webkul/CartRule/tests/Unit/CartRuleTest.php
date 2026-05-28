<?php

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Webkul\CartRule\Models\CartRule;

it('allows mass assignment of fillable fields', function () {
    $rule = new CartRule([
        'name'            => 'Summer Sale',
        'description'     => '10% off all orders',
        'status'          => 1,
        'coupon_type'     => 1,
        'action_type'     => 'by_percent',
        'discount_amount' => 10.00,
        'sort_order'      => 1,
    ]);

    expect($rule->name)->toBe('Summer Sale')
        ->and($rule->action_type)->toBe('by_percent')
        ->and($rule->discount_amount)->toBe(10.00);
});

it('casts conditions to array', function () {
    $rule = new CartRule(['conditions' => ['attribute' => 'subtotal']]);

    expect($rule->conditions)->toBeArray();
});

it('has many cart_rule_channels relationship', function () {
    expect((new CartRule)->cart_rule_channels())->toBeInstanceOf(BelongsToMany::class);
});

it('has many cart_rule_customer_groups relationship', function () {
    expect((new CartRule)->cart_rule_customer_groups())->toBeInstanceOf(BelongsToMany::class);
});

it('has one coupon_code relationship', function () {
    expect((new CartRule)->coupon_code())->toBeInstanceOf(HasOne::class);
});

it('builds a model via factory make', function () {
    $rule = CartRule::factory()->make();

    expect((string) $rule->name)->toBeString()
        ->and($rule->status)->toBeIn(['0', '1', 1, 0]);
});
