<?php

use Webkul\Checkout\Models\CartPayment;

it('uses cart_payment table', function () {
    expect((new CartPayment)->getTable())->toBe('cart_payment');
});

it('builds a model via factory make', function () {
    $payment = CartPayment::factory()->make();

    expect($payment)->toBeInstanceOf(CartPayment::class);
});
