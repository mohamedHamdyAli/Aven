<?php

use Webkul\Inventory\Models\InventorySource;

it('allows assignment via guarded model', function () {
    $source = new InventorySource([
        'code'   => 'main-warehouse',
        'name'   => 'Main Warehouse',
        'status' => 1,
    ]);

    expect($source->code)->toBe('main-warehouse')
        ->and($source->name)->toBe('Main Warehouse');
});

it('creates a record via factory', function () {
    $source = InventorySource::factory()->create();

    expect($source->exists)->toBeTrue()
        ->and($source->code)->toBeString();
});
