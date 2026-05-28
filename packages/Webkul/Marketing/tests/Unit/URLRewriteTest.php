<?php

use Webkul\Marketing\Models\URLRewrite;

it('allows mass assignment of fillable fields', function () {
    $rewrite = new URLRewrite([
        'entity_type'   => 'product',
        'request_path'  => 'old-product-url',
        'target_path'   => 'new-product-url',
        'redirect_type' => '301',
        'locale'        => 'en',
    ]);

    expect($rewrite->entity_type)->toBe('product')
        ->and($rewrite->request_path)->toBe('old-product-url')
        ->and($rewrite->redirect_type)->toBe('301');
});

it('creates a record via factory', function () {
    $rewrite = URLRewrite::factory()->create();

    expect($rewrite->exists)->toBeTrue()
        ->and($rewrite->request_path)->toBeString();
});
