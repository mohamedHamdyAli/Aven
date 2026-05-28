<?php

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Webkul\Sales\Models\Order;

it('has many items relationship', function () {
    expect((new Order)->items())->toBeInstanceOf(HasMany::class);
});

it('has many shipments relationship', function () {
    expect((new Order)->shipments())->toBeInstanceOf(HasMany::class);
});

it('has many invoices relationship', function () {
    expect((new Order)->invoices())->toBeInstanceOf(HasMany::class);
});

it('has many refunds relationship', function () {
    expect((new Order)->refunds())->toBeInstanceOf(HasMany::class);
});

it('has one payment relationship', function () {
    expect((new Order)->payment())->toBeInstanceOf(HasOne::class);
});

it('has many addresses relationship', function () {
    expect((new Order)->addresses())->toBeInstanceOf(HasMany::class);
});

it('builds a model via factory make', function () {
    $order = Order::factory()->make();

    expect($order)->toBeInstanceOf(Order::class)
        ->and($order->status)->toBeString();
});
