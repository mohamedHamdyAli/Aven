<?php

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Webkul\FlashSale\Models\FlashSale;

it('allows mass assignment of fillable fields', function () {
    $sale = new FlashSale([
        'name'             => 'Summer Flash Sale',
        'discount_percent' => 25.0,
        'starts_at'        => now()->subHour(),
        'ends_at'          => now()->addHours(6),
        'active'           => true,
    ]);

    expect($sale->name)->toBe('Summer Flash Sale')
        ->and($sale->discount_percent)->toBe(25.0)
        ->and($sale->active)->toBeTrue();
});

it('casts active to boolean', function () {
    $sale = new FlashSale(['active' => 1]);

    expect($sale->active)->toBeTrue();
});

it('casts discount_percent to float', function () {
    $sale = new FlashSale(['discount_percent' => '20']);

    expect($sale->discount_percent)->toBeFloat();
});

it('casts starts_at and ends_at to datetime', function () {
    $sale = new FlashSale([
        'starts_at' => '2024-07-01 09:00:00',
        'ends_at'   => '2024-07-01 21:00:00',
    ]);

    expect($sale->starts_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class)
        ->and($sale->ends_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

it('has many products relationship', function () {
    expect((new FlashSale)->products())->toBeInstanceOf(BelongsToMany::class);
});

it('isRunning returns true for active sale within time window', function () {
    $sale = FlashSale::factory()->create([
        'active'    => true,
        'starts_at' => now()->subHour(),
        'ends_at'   => now()->addHour(),
    ]);

    expect($sale->isRunning())->toBeTrue();
});

it('isRunning returns false for inactive sale', function () {
    $sale = FlashSale::factory()->inactive()->create();

    expect($sale->isRunning())->toBeFalse();
});

it('isRunning returns false for expired sale', function () {
    $sale = FlashSale::factory()->expired()->create(['active' => true]);

    expect($sale->isRunning())->toBeFalse();
});

it('creates a record via factory', function () {
    $sale = FlashSale::factory()->create();

    expect($sale->exists)->toBeTrue();
});
