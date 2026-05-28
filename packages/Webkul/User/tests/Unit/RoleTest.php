<?php

use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\User\Models\Role;

it('allows mass assignment of fillable fields', function () {
    $role = new Role([
        'name'            => 'Editor',
        'description'     => 'Can edit content',
        'permission_type' => 'custom',
        'permissions'     => ['catalog', 'sales'],
    ]);

    expect($role->name)->toBe('Editor')
        ->and($role->permission_type)->toBe('custom')
        ->and($role->permissions)->toBe(['catalog', 'sales']);
});

it('casts permissions to array', function () {
    $role = new Role(['permissions' => ['catalog', 'sales']]);

    expect($role->permissions)->toBeArray()
        ->and($role->permissions)->toContain('catalog');
});

it('has many admins relationship', function () {
    expect((new Role)->admins())->toBeInstanceOf(HasMany::class);
});

it('creates a record via factory', function () {
    $role = Role::factory()->create();

    expect($role->exists)->toBeTrue()
        ->and($role->name)->toBeString();
});
