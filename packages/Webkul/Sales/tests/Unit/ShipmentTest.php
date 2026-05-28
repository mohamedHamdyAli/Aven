<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Sales\Models\Shipment;

it('has an order relationship', function () {
    expect((new Shipment)->order())->toBeInstanceOf(BelongsTo::class);
});

it('has many items relationship', function () {
    expect((new Shipment)->items())->toBeInstanceOf(HasMany::class);
});

it('has an inventory_source relationship', function () {
    expect((new Shipment)->inventory_source())->toBeInstanceOf(BelongsTo::class);
});

it('builds a model via factory make', function () {
    $shipment = Shipment::factory()->make();

    expect($shipment)->toBeInstanceOf(Shipment::class);
});
