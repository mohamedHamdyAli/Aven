<?php

use Webkul\EgyptShipping\Models\EgyptGovernorate;

it('allows mass assignment of fillable fields', function () {
    $gov = new EgyptGovernorate([
        'code'      => 'CAI',
        'name_ar'   => 'القاهرة',
        'name_en'   => 'Cairo',
        'rate'      => 30.00,
        'is_active' => true,
    ]);

    expect($gov->code)->toBe('CAI')
        ->and($gov->name_en)->toBe('Cairo')
        ->and($gov->is_active)->toBeTrue();
});

it('casts is_active to boolean', function () {
    $gov = new EgyptGovernorate(['is_active' => 1]);

    expect($gov->is_active)->toBeTrue();
});

it('casts rate to decimal', function () {
    $gov = new EgyptGovernorate(['rate' => '45.50']);

    expect($gov->rate)->toBeNumeric();
});

it('creates a record via factory', function () {
    $gov = EgyptGovernorate::factory()->create();

    expect($gov->exists)->toBeTrue()
        ->and($gov->is_active)->toBeTrue();
});

it('inactive state sets is_active to false', function () {
    $gov = EgyptGovernorate::factory()->inactive()->create();

    expect($gov->is_active)->toBeFalse();
});
