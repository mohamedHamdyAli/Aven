<?php

use Webkul\Core\Models\Locale;

it('allows mass assignment of fillable fields', function () {
    $locale = new Locale([
        'code'      => 'ar',
        'name'      => 'Arabic',
        'direction' => 'rtl',
    ]);

    expect($locale->code)->toBe('ar')
        ->and($locale->name)->toBe('Arabic')
        ->and($locale->direction)->toBe('rtl');
});

it('creates a record via factory', function () {
    $locale = Locale::factory()->create();

    expect($locale->exists)->toBeTrue()
        ->and($locale->code)->toBeString();
});
