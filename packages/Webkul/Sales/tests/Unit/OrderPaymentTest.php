<?php

use Webkul\Sales\Models\OrderPayment;

it('allows mass assignment via guarded model', function () {
    $payment = new OrderPayment([
        'method'       => 'cashondelivery',
        'method_title' => 'Cash on Delivery',
    ]);

    expect($payment->method)->toBe('cashondelivery');
});

it('casts additional to array', function () {
    $payment = new OrderPayment;
    $payment->additional = ['note' => 'test'];

    expect($payment->additional)->toBeArray();
});

it('uses order_payment table', function () {
    expect((new OrderPayment)->getTable())->toBe('order_payment');
});

it('builds a model via factory make', function () {
    $payment = OrderPayment::factory()->make();

    expect($payment)->toBeInstanceOf(OrderPayment::class);
});
