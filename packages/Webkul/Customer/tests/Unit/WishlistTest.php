<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Customer\Models\Wishlist;

it('allows mass assignment of fillable fields', function () {
    $item = new Wishlist([
        'additional'  => ['qty' => 1],
        'customer_id' => null,
        'product_id'  => null,
        'channel_id'  => null,
    ]);

    expect($item->additional)->toBeArray()
        ->and($item->additional['qty'])->toBe(1);
});

it('casts additional to array', function () {
    $item = new Wishlist(['additional' => ['option' => 'red']]);

    expect($item->additional)->toBeArray();
});

it('has a product relationship', function () {
    expect((new Wishlist)->product())->toBeInstanceOf(BelongsTo::class);
});

it('has a customer relationship', function () {
    expect((new Wishlist)->customer())->toBeInstanceOf(BelongsTo::class);
});

it('builds a model via factory make', function () {
    $item = Wishlist::factory()->make();

    expect($item)->toBeInstanceOf(Wishlist::class);
});
