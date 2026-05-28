<?php

use Webkul\BulkDeal\Models\BulkDeal;

it('allows mass assignment of fillable fields', function () {
    $deal = new BulkDeal([
        'name'          => 'Buy 10 Get 15',
        'description'   => 'Bulk pricing deal',
        'status'        => true,
        'paid_quantity' => 3,
        'deal_quantity' => 5,
        'deal_price'    => 199.99,
        'starts_from'   => now(),
        'ends_till'     => now()->addMonth(),
        'sort_order'    => 1,
    ]);

    expect($deal->name)->toBe('Buy 10 Get 15')
        ->and($deal->paid_quantity)->toBe(3)
        ->and($deal->deal_quantity)->toBe(5);
});

it('casts status to boolean', function () {
    $deal = new BulkDeal(['status' => 1]);

    expect($deal->status)->toBeTrue();
});

it('casts deal_price to float', function () {
    $deal = new BulkDeal(['deal_price' => '99.99']);

    expect($deal->deal_price)->toBeFloat();
});

it('casts starts_from and ends_till to datetime', function () {
    $deal = new BulkDeal([
        'starts_from' => '2024-01-01 00:00:00',
        'ends_till'   => '2024-12-31 23:59:59',
    ]);

    expect($deal->starts_from)->toBeInstanceOf(\Illuminate\Support\Carbon::class)
        ->and($deal->ends_till)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

it('creates a record via factory', function () {
    $deal = BulkDeal::factory()->create();

    expect($deal->exists)->toBeTrue()
        ->and($deal->status)->toBeTrue();
});

it('inactive state sets status to false', function () {
    $deal = BulkDeal::factory()->inactive()->create();

    expect($deal->status)->toBeFalse();
});
