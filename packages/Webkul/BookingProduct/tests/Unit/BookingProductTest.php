<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\BookingProduct\Models\BookingProduct;

it('has a product relationship', function () {
    expect((new BookingProduct)->product())->toBeInstanceOf(BelongsTo::class);
});

it('instantiates via new with attributes', function () {
    $bookingProduct = new BookingProduct([
        'product_id' => null,
        'type'       => 'default',
    ]);

    expect($bookingProduct)->toBeInstanceOf(BookingProduct::class);
});
