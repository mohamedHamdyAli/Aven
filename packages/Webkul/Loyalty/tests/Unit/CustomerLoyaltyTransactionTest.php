<?php

use Webkul\Loyalty\Models\CustomerLoyaltyTransaction;

it('allows mass assignment of fillable fields', function () {
    $tx = new CustomerLoyaltyTransaction([
        'customer_id'   => null,
        'order_id'      => null,
        'type'          => 'earned',
        'points'        => 100,
        'balance_after' => 600,
        'description'   => 'Order reward',
    ]);

    expect($tx->type)->toBe('earned')
        ->and($tx->points)->toBe(100)
        ->and($tx->balance_after)->toBe(600);
});

it('builds a model via factory make', function () {
    $tx = CustomerLoyaltyTransaction::factory()->make();

    expect($tx->type)->toBeIn(['earned', 'redeemed', 'adjusted']);
});
