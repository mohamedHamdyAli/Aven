<?php

use Webkul\Tax\Models\TaxMap;

it('allows mass assignment of fillable fields', function () {
    $map = new TaxMap([
        'tax_category_id' => 1,
        'tax_rate_id'     => 2,
    ]);

    expect($map->tax_category_id)->toBe(1)
        ->and($map->tax_rate_id)->toBe(2);
});

it('instantiates with pivot ids', function () {
    $map = new TaxMap(['tax_category_id' => 1, 'tax_rate_id' => 2]);

    expect($map->tax_category_id)->toBe(1)
        ->and($map->tax_rate_id)->toBe(2);
});
