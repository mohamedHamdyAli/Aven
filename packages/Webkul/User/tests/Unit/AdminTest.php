<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\User\Models\Admin;

it('allows mass assignment of fillable fields', function () {
    $admin = new Admin([
        'name'   => 'Ahmed Admin',
        'email'  => 'admin@aven.test',
        'status' => 1,
    ]);

    expect($admin->name)->toBe('Ahmed Admin')
        ->and($admin->email)->toBe('admin@aven.test');
});

it('casts two_factor_enabled to boolean', function () {
    $admin = new Admin(['two_factor_enabled' => 1]);

    expect($admin->two_factor_enabled)->toBeTrue();
});

it('casts two_factor_backup_codes to array', function () {
    $admin = new Admin(['two_factor_backup_codes' => ['code1', 'code2']]);

    expect($admin->two_factor_backup_codes)->toBeArray();
});

it('has a role relationship', function () {
    expect((new Admin)->role())->toBeInstanceOf(BelongsTo::class);
});

it('creates a record via factory', function () {
    $admin = Admin::factory()->create();

    expect($admin->exists)->toBeTrue()
        ->and($admin->name)->toBeString()
        ->and($admin->email)->toBeString();
});
