<?php

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Webkul\Tax\Models\TaxCategory;

it('allows mass assignment of fillable fields', function () {
    $category = new TaxCategory([
        'code'        => 'shipping',
        'name'        => 'Shipping Tax',
        'description' => 'Tax applied to shipping costs',
    ]);

    expect($category->code)->toBe('shipping')
        ->and($category->name)->toBe('Shipping Tax')
        ->and($category->description)->toBe('Tax applied to shipping costs');
});

it('has many tax_rates relationship', function () {
    expect((new TaxCategory)->tax_rates())->toBeInstanceOf(BelongsToMany::class);
});

it('creates a record via factory', function () {
    $category = TaxCategory::factory()->create();

    expect($category->exists)->toBeTrue()
        ->and($category->code)->toBeString()
        ->and($category->name)->toBeString();
});
