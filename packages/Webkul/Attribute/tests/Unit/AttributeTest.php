<?php

use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Attribute\Models\Attribute;

it('allows mass assignment of fillable fields', function () {
    $attribute = new Attribute([
        'code'               => 'color',
        'admin_name'         => 'Color',
        'type'               => 'select',
        'is_required'        => false,
        'is_unique'          => false,
        'is_filterable'      => true,
        'is_configurable'    => true,
        'is_user_defined'    => true,
        'value_per_locale'   => false,
        'value_per_channel'  => false,
    ]);

    expect($attribute->code)->toBe('color')
        ->and($attribute->type)->toBe('select')
        ->and($attribute->is_filterable)->toBeTrue();
});

it('has many options relationship', function () {
    expect((new Attribute)->options())->toBeInstanceOf(HasMany::class);
});

it('creates a record via factory', function () {
    $attribute = Attribute::factory()->create();

    expect($attribute->exists)->toBeTrue()
        ->and($attribute->code)->toBeString();
});
