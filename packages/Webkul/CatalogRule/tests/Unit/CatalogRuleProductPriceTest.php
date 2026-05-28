<?php

use Webkul\CatalogRule\Models\CatalogRuleProductPrice;

it('allows mass assignment of fillable fields', function () {
    $price = new CatalogRuleProductPrice([
        'price'            => 79.99,
        'rule_date'        => '2025-11-28',
        'catalog_rule_id'  => null,
        'channel_id'       => null,
        'customer_group_id' => null,
        'product_id'       => null,
    ]);

    expect($price->price)->toBe(79.99);
});
