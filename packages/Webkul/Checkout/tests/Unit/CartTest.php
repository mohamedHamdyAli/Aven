<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Webkul\Checkout\Models\Cart;

it('has a customer relationship', function () {
    expect((new Cart)->customer())->toBeInstanceOf(BelongsTo::class);
});

it('has a channel relationship', function () {
    expect((new Cart)->channel())->toBeInstanceOf(BelongsTo::class);
});

it('has many items relationship', function () {
    expect((new Cart)->items())->toBeInstanceOf(HasMany::class);
});

it('has one billing_address relationship', function () {
    expect((new Cart)->billing_address())->toBeInstanceOf(HasOne::class);
});

it('has one shipping_address relationship', function () {
    expect((new Cart)->shipping_address())->toBeInstanceOf(HasOne::class);
});

it('has one payment relationship', function () {
    expect((new Cart)->payment())->toBeInstanceOf(HasOne::class);
});

it('builds a model via factory make', function () {
    $cart = Cart::factory()->make();

    expect($cart)->toBeInstanceOf(Cart::class);
});
