<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Webkul\RMA\Models\RMA;

it('allows mass assignment of fillable fields', function () {
    $rma = new RMA([
        'information'       => 'Product arrived damaged',
        'rma_status_id'     => null,
        'order_id'          => null,
        'status'            => 'pending',
        'package_condition' => 'damaged',
    ]);

    expect($rma->information)->toBe('Product arrived damaged')
        ->and($rma->status)->toBe('pending');
});

it('has a status relationship', function () {
    expect((new RMA)->status())->toBeInstanceOf(BelongsTo::class);
});

it('has an order relationship', function () {
    expect((new RMA)->order())->toBeInstanceOf(BelongsTo::class);
});

it('has many messages relationship', function () {
    expect((new RMA)->messages())->toBeInstanceOf(HasMany::class);
});

it('has one item relationship', function () {
    expect((new RMA)->item())->toBeInstanceOf(HasOne::class);
});

it('has many images relationship', function () {
    expect((new RMA)->images())->toBeInstanceOf(HasMany::class);
});
