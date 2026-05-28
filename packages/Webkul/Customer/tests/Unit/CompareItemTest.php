<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Customer\Models\CompareItem;

it('allows mass assignment of fillable fields', function () {
    $item = new CompareItem([
        'customer_id' => null,
        'product_id'  => null,
    ]);

    expect($item)->toBeInstanceOf(CompareItem::class);
});

it('has a product relationship', function () {
    expect((new CompareItem)->product())->toBeInstanceOf(BelongsTo::class);
});

it('has a customer relationship', function () {
    expect((new CompareItem)->customer())->toBeInstanceOf(BelongsTo::class);
});
