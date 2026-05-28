<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Product\Models\Product;

it('allows mass assignment of fillable fields', function () {
    $product = new Product([
        'type'               => 'simple',
        'attribute_family_id' => 1,
        'sku'                => 'TEST-SKU-001',
        'parent_id'          => null,
    ]);

    expect($product->type)->toBe('simple')
        ->and($product->sku)->toBe('TEST-SKU-001');
});

it('has an attribute_family relationship', function () {
    expect((new Product)->attribute_family())->toBeInstanceOf(BelongsTo::class);
});

it('has a parent relationship', function () {
    expect((new Product)->parent())->toBeInstanceOf(BelongsTo::class);
});

it('has many variants relationship', function () {
    expect((new Product)->variants())->toBeInstanceOf(HasMany::class);
});

it('has many categories relationship', function () {
    expect((new Product)->categories())->toBeInstanceOf(BelongsToMany::class);
});

it('has many images relationship', function () {
    expect((new Product)->images())->toBeInstanceOf(HasMany::class);
});

it('has many reviews relationship', function () {
    expect((new Product)->reviews())->toBeInstanceOf(HasMany::class);
});

it('has many inventories relationship', function () {
    expect((new Product)->inventories())->toBeInstanceOf(HasMany::class);
});

it('builds a simple product via factory make', function () {
    $product = Product::factory()->simple()->make();

    expect($product->type)->toBe('simple')
        ->and((string) $product->sku)->toBeString();
});
