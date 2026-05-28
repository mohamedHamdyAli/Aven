<?php

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Webkul\Category\Models\Category;

it('allows mass assignment of fillable fields', function () {
    $category = new Category([
        'position'     => 1,
        'status'       => 1,
        'display_mode' => 'products_and_description',
        'parent_id'    => null,
        'additional'   => null,
    ]);

    expect($category->position)->toBe(1)
        ->and($category->status)->toBe(1)
        ->and($category->display_mode)->toBe('products_and_description');
});

it('has many products relationship', function () {
    expect((new Category)->products())->toBeInstanceOf(BelongsToMany::class);
});

it('has many filterableAttributes relationship', function () {
    expect((new Category)->filterableAttributes())->toBeInstanceOf(BelongsToMany::class);
});

it('creates a record via factory', function () {
    $category = Category::factory()->create();

    expect($category->exists)->toBeTrue();
});
