<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\ShopTheLook\Models\ProductLookItem;

it('allows mass assignment of fillable fields', function () {
    $item = new ProductLookItem([
        'product_id'      => 1,
        'look_product_id' => 2,
        'sort_order'      => 3,
    ]);

    expect($item->product_id)->toBe(1)
        ->and($item->look_product_id)->toBe(2)
        ->and($item->sort_order)->toBe(3);
});

it('disables timestamps', function () {
    expect((new ProductLookItem)->timestamps)->toBeFalse();
});

it('has a lookProduct relationship', function () {
    expect((new ProductLookItem)->lookProduct())->toBeInstanceOf(BelongsTo::class);
});

it('builds a model via factory make', function () {
    $item = ProductLookItem::factory()->make([
        'product_id'      => 1,
        'look_product_id' => 2,
    ]);

    expect($item->sort_order)->toBeInt();
});
