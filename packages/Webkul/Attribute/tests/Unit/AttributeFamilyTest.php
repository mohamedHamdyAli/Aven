<?php

use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Attribute\Models\AttributeFamily;

it('allows mass assignment of fillable fields', function () {
    $family = new AttributeFamily([
        'code' => 'default',
        'name' => 'Default',
    ]);

    expect($family->code)->toBe('default')
        ->and($family->name)->toBe('Default');
});

it('has many attribute_groups relationship', function () {
    expect((new AttributeFamily)->attribute_groups())->toBeInstanceOf(HasMany::class);
});

it('has many products relationship', function () {
    expect((new AttributeFamily)->products())->toBeInstanceOf(HasMany::class);
});

it('creates a record via factory', function () {
    $family = AttributeFamily::factory()->create();

    expect($family->exists)->toBeTrue()
        ->and($family->code)->toBeString();
});
