<?php

use Webkul\Affiliate\Models\Affiliate;
use Webkul\Affiliate\Models\AffiliateCommission;

it('allows mass assignment of fillable fields', function () {
    $commission = new AffiliateCommission([
        'affiliate_id' => 1,
        'order_id'     => null,
        'order_total'  => 500.00,
        'commission'   => 50.00,
        'status'       => 'pending',
    ]);

    expect($commission->order_total)->toBeNumeric()
        ->and($commission->commission)->toBeNumeric()
        ->and($commission->status)->toBe('pending');
});

it('builds a model via factory make', function () {
    $commission = AffiliateCommission::factory()->make(['affiliate_id' => 1]);

    expect($commission->status)->toBeIn(['pending', 'approved', 'paid'])
        ->and($commission->order_total)->toBeNumeric();
});

it('approved state sets status to approved', function () {
    $commission = AffiliateCommission::factory()->approved()->make();

    expect($commission->status)->toBe('approved');
});
