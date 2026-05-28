<?php

use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Loyalty\Models\CustomerLoyaltyPoints;

it('allows mass assignment of fillable fields', function () {
    $points = new CustomerLoyaltyPoints([
        'customer_id' => null,
        'balance'     => 500,
    ]);

    expect($points->balance)->toBe(500);
});

it('has many transactions relationship', function () {
    expect((new CustomerLoyaltyPoints)->transactions())->toBeInstanceOf(HasMany::class);
});

it('builds a model via factory make', function () {
    $points = CustomerLoyaltyPoints::factory()->make();

    expect($points->balance)->toBeInt();
});
