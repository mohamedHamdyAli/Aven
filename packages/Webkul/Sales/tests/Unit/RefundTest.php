<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Sales\Models\Refund;

it('has an order relationship', function () {
    expect((new Refund)->order())->toBeInstanceOf(BelongsTo::class);
});

it('has many items relationship', function () {
    expect((new Refund)->items())->toBeInstanceOf(HasMany::class);
});

it('builds a model via factory make', function () {
    $refund = Refund::factory()->make();

    expect($refund)->toBeInstanceOf(Refund::class);
});
