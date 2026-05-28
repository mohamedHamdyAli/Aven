<?php

use Webkul\CatalogRule\Models\CatalogRule;

it('allows mass assignment of fillable fields', function () {
    $rule = new CatalogRule([
        'name'        => 'Black Friday',
        'description' => '20% off electronics',
        'starts_from' => '2025-11-28',
        'ends_till'   => '2025-11-30',
        'status'      => 1,
        'discount_amount' => 20.00,
    ]);

    expect($rule->name)->toBe('Black Friday')
        ->and($rule->discount_amount)->toBe(20.00);
});

it('builds a model via factory make', function () {
    $rule = CatalogRule::factory()->make();

    expect($rule->name)->toBeString();
});
