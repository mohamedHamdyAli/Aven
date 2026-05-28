<?php

use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Customer\Models\CustomerGroup;

it('allows mass assignment of fillable fields', function () {
    $group = new CustomerGroup([
        'name'            => 'Wholesale',
        'code'            => 'wholesale',
        'is_user_defined' => true,
    ]);

    expect($group->name)->toBe('Wholesale')
        ->and($group->code)->toBe('wholesale');
});

it('has many customers relationship', function () {
    expect((new CustomerGroup)->customers())->toBeInstanceOf(HasMany::class);
});

it('creates a record via factory', function () {
    $group = CustomerGroup::factory()->create();

    expect($group->exists)->toBeTrue()
        ->and($group->name)->toBeString();
});
