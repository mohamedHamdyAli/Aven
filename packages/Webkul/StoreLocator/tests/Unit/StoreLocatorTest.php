<?php

use Webkul\StoreLocator\Models\StoreLocator;

it('allows mass assignment of fillable fields', function () {
    $store = new StoreLocator([
        'name'          => 'Aven Cairo Branch',
        'address'       => '10 Tahrir Square, Cairo',
        'latitude'      => 30.0444,
        'longitude'     => 31.2357,
        'phone'         => '+201234567890',
        'working_hours' => ['Mon-Fri' => '9am-9pm'],
        'image'         => null,
        'status'        => true,
    ]);

    expect($store->name)->toBe('Aven Cairo Branch')
        ->and($store->latitude)->toBe(30.0444)
        ->and($store->status)->toBeTrue();
});

it('casts status to boolean', function () {
    $store = new StoreLocator(['status' => 1]);

    expect($store->status)->toBeTrue();
});

it('casts latitude and longitude to float', function () {
    $store = new StoreLocator(['latitude' => '30.044', 'longitude' => '31.235']);

    expect($store->latitude)->toBeFloat()
        ->and($store->longitude)->toBeFloat();
});

it('casts working_hours to array', function () {
    $store = new StoreLocator(['working_hours' => ['Mon-Sun' => '10am-8pm']]);

    expect($store->working_hours)->toBeArray()
        ->and($store->working_hours['Mon-Sun'])->toBe('10am-8pm');
});

it('builds a model via factory make', function () {
    $store = StoreLocator::factory()->make();

    expect($store->name)->toBeString()
        ->and($store->status)->toBeTrue();
});

it('inactive state sets status to false', function () {
    $store = StoreLocator::factory()->inactive()->make();

    expect($store->status)->toBeFalse();
});
