<?php

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Webkul\Tax\Models\TaxRate;

it('allows mass assignment of fillable fields', function () {
    $rate = new TaxRate([
        'identifier' => 'EG-VAT',
        'is_zip'     => false,
        'zip_code'   => null,
        'zip_from'   => null,
        'zip_to'     => null,
        'state'      => null,
        'country'    => 'EG',
        'tax_rate'   => 14.00,
    ]);

    expect($rate->identifier)->toBe('EG-VAT')
        ->and($rate->country)->toBe('EG')
        ->and($rate->tax_rate)->toBe(14.00);
});

it('has many tax_categories relationship', function () {
    expect((new TaxRate)->tax_categories())->toBeInstanceOf(BelongsToMany::class);
});

it('creates a record via factory', function () {
    $rate = TaxRate::factory()->create();

    expect($rate->exists)->toBeTrue()
        ->and($rate->identifier)->toBeString();
});
