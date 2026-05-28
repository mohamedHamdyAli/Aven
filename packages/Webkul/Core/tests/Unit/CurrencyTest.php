<?php

use Illuminate\Database\Eloquent\Relations\HasOne;
use Webkul\Core\Models\Currency;

it('allows mass assignment of fillable fields', function () {
    $currency = new Currency([
        'code'   => 'usd',
        'name'   => 'US Dollar',
        'symbol' => '$',
    ]);

    expect($currency->name)->toBe('US Dollar')
        ->and($currency->symbol)->toBe('$');
});

it('setCodeAttribute uppercases the currency code', function () {
    $currency = new Currency(['code' => 'usd']);

    expect($currency->code)->toBe('USD');
});

it('has one exchange_rate relationship', function () {
    expect((new Currency)->exchange_rate())->toBeInstanceOf(HasOne::class);
});

it('creates a record via factory', function () {
    $currency = Currency::factory()->create();

    expect($currency->exists)->toBeTrue()
        ->and($currency->code)->toBeString();
});
