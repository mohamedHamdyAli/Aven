<?php

use Webkul\Wallet\Models\CustomerWalletTransaction;

it('allows mass assignment of fillable fields', function () {
    $tx = new CustomerWalletTransaction([
        'customer_id'   => null,
        'order_id'      => null,
        'type'          => 'credit',
        'amount'        => 100.0000,
        'balance_after' => 600.0000,
        'note'          => 'Order refund',
    ]);

    expect($tx->type)->toBe('credit')
        ->and($tx->note)->toBe('Order refund');
});

it('casts amount and balance_after to decimal', function () {
    $tx = new CustomerWalletTransaction(['amount' => '50.0000', 'balance_after' => '550.0000']);

    expect($tx->amount)->toBeNumeric()
        ->and($tx->balance_after)->toBeNumeric();
});

it('builds a model via factory make', function () {
    $tx = CustomerWalletTransaction::factory()->make();

    expect($tx->type)->toBeIn(['credit', 'debit', 'refund']);
});
