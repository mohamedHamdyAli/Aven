<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Sales\Models\Invoice;

it('has an order relationship', function () {
    expect((new Invoice)->order())->toBeInstanceOf(BelongsTo::class);
});

it('has many items relationship', function () {
    expect((new Invoice)->items())->toBeInstanceOf(HasMany::class);
});

it('builds a model via factory make', function () {
    $invoice = Invoice::factory()->make();

    expect($invoice)->toBeInstanceOf(Invoice::class);
});
