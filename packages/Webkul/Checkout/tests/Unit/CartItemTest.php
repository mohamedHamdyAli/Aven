<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Webkul\Checkout\Models\CartItem;

it('has a cart relationship (HasOne from item)', function () {
    expect((new CartItem)->cart())->toBeInstanceOf(HasOne::class);
});

it('has a parent relationship', function () {
    expect((new CartItem)->parent())->toBeInstanceOf(BelongsTo::class);
});

it('has many children relationship', function () {
    expect((new CartItem)->children())->toBeInstanceOf(HasMany::class);
});

it('has a child relationship (BelongsTo)', function () {
    expect((new CartItem)->child())->toBeInstanceOf(BelongsTo::class);
});

it('builds a model via factory make', function () {
    $item = CartItem::factory()->make();

    expect($item)->toBeInstanceOf(CartItem::class);
});
