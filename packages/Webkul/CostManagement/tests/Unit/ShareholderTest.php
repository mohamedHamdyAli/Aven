<?php

use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\CostManagement\Models\Shareholder;

it('allows mass assignment of fillable fields', function () {
    $shareholder = new Shareholder([
        'name'       => 'Ahmed Ali',
        'email'      => 'ahmed@example.com',
        'phone'      => '+201234567890',
        'percentage' => 40.0,
        'active'     => true,
        'notes'      => null,
        'joined_at'  => '2022-01-01',
    ]);

    expect($shareholder->name)->toBe('Ahmed Ali')
        ->and($shareholder->percentage)->toBe(40.0)
        ->and($shareholder->active)->toBeTrue();
});

it('casts active to boolean', function () {
    $s = new Shareholder(['active' => 1]);

    expect($s->active)->toBeTrue();
});

it('casts joined_at to date', function () {
    $s = new Shareholder(['joined_at' => '2022-01-01']);

    expect($s->joined_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

it('casts percentage to float', function () {
    $s = new Shareholder(['percentage' => '25']);

    expect($s->percentage)->toBeFloat();
});

it('has many distributionItems relationship', function () {
    expect((new Shareholder)->distributionItems())->toBeInstanceOf(HasMany::class);
});

it('totalEarned returns sum of distribution items', function () {
    $shareholder = Shareholder::factory()->create();

    expect($shareholder->totalEarned())->toBeFloat()
        ->and($shareholder->totalEarned())->toBe(0.0);
});

it('creates a record via factory', function () {
    $shareholder = Shareholder::factory()->create();

    expect($shareholder->exists)->toBeTrue()
        ->and($shareholder->active)->toBeTrue();
});

it('inactive state sets active to false', function () {
    $shareholder = Shareholder::factory()->inactive()->create();

    expect($shareholder->active)->toBeFalse();
});
