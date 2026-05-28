<?php

use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Wallet\Models\CustomerWallet;

it('allows mass assignment of fillable fields', function () {
    $wallet = new CustomerWallet([
        'customer_id' => null,
        'balance'     => 500.0000,
    ]);

    expect($wallet->balance)->toBeNumeric();
});

it('casts balance to decimal', function () {
    $wallet = new CustomerWallet(['balance' => '200.5000']);

    expect($wallet->balance)->toBeNumeric();
});

it('has many transactions relationship', function () {
    expect((new CustomerWallet)->transactions())->toBeInstanceOf(HasMany::class);
});

it('builds a model via factory make', function () {
    $wallet = CustomerWallet::factory()->make();

    expect($wallet->balance)->toBeNumeric();
});
