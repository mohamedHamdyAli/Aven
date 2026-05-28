<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\CostManagement\Models\ProductCost;

it('allows mass assignment of fillable fields', function () {
    $cost = new ProductCost([
        'product_id'             => null,
        'cost_price'             => 80.00,
        'manufacturing_fee'      => 10.00,
        'shipping_cost_per_unit' => 5.00,
        'other_costs'            => 2.00,
        'notes'                  => 'Raw material cost',
    ]);

    expect($cost->cost_price)->toBe(80.00)
        ->and($cost->manufacturing_fee)->toBe(10.00)
        ->and($cost->notes)->toBe('Raw material cost');
});

it('casts all cost fields to float', function () {
    $cost = new ProductCost([
        'cost_price'             => '80',
        'manufacturing_fee'      => '10',
        'shipping_cost_per_unit' => '5',
        'other_costs'            => '2',
    ]);

    expect($cost->cost_price)->toBeFloat()
        ->and($cost->manufacturing_fee)->toBeFloat()
        ->and($cost->shipping_cost_per_unit)->toBeFloat()
        ->and($cost->other_costs)->toBeFloat();
});

it('total_cost accessor sums all cost fields', function () {
    $cost = new ProductCost([
        'cost_price'             => 80.00,
        'manufacturing_fee'      => 10.00,
        'shipping_cost_per_unit' => 5.00,
        'other_costs'            => 2.00,
    ]);

    expect($cost->total_cost)->toBe(97.00);
});

it('has a product relationship', function () {
    expect((new ProductCost)->product())->toBeInstanceOf(BelongsTo::class);
});

it('builds a model via factory make', function () {
    $cost = ProductCost::factory()->make();

    expect($cost->cost_price)->toBeFloat();
});
