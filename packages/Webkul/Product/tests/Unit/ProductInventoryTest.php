<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Product\Models\ProductInventory;

it('allows mass assignment of fillable fields', function () {
    $inventory = new ProductInventory([
        'qty'                 => 100,
        'product_id'          => null,
        'inventory_source_id' => null,
        'vendor_id'           => null,
    ]);

    expect($inventory->qty)->toBe(100);
});

it('disables timestamps', function () {
    expect((new ProductInventory)->timestamps)->toBeFalse();
});

it('has a product relationship', function () {
    expect((new ProductInventory)->product())->toBeInstanceOf(BelongsTo::class);
});

it('has an inventory_source relationship', function () {
    expect((new ProductInventory)->inventory_source())->toBeInstanceOf(BelongsTo::class);
});

it('builds a model via factory make', function () {
    $inventory = ProductInventory::factory()->make();

    expect($inventory->qty)->toBeInt();
});
