<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Webkul\Sales\Models\OrderItem;

it('casts additional to array', function () {
    $item = new OrderItem(['additional' => ['qty' => 1]]);

    expect($item->additional)->toBeArray();
});

it('has an order relationship', function () {
    expect((new OrderItem)->order())->toBeInstanceOf(BelongsTo::class);
});

it('has a parent relationship', function () {
    expect((new OrderItem)->parent())->toBeInstanceOf(BelongsTo::class);
});

it('has many children relationship', function () {
    expect((new OrderItem)->children())->toBeInstanceOf(HasMany::class);
});

it('has one child relationship', function () {
    expect((new OrderItem)->child())->toBeInstanceOf(HasOne::class);
});

it('builds a model via factory make', function () {
    $item = OrderItem::factory()->make();

    expect($item)->toBeInstanceOf(OrderItem::class);
});
