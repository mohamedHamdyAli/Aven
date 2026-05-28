<?php

use Webkul\CatalogRule\Models\CatalogRuleProduct;

it('allows mass assignment of fillable fields', function () {
    $ruleProduct = new CatalogRuleProduct([
        'starts_from'      => '2025-11-28',
        'ends_till'        => '2025-11-30',
        'catalog_rule_id'  => null,
        'channel_id'       => null,
        'customer_group_id' => null,
        'product_id'       => null,
        'discount_amount'  => 20.00,
    ]);

    expect($ruleProduct->discount_amount)->toBe(20.00);
});
