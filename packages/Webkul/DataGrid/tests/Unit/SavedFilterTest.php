<?php

use Webkul\DataGrid\Models\SavedFilter;

it('allows mass assignment of fillable fields', function () {
    $filter = new SavedFilter([
        'user_id' => 1,
        'src'     => 'admin::catalog.products',
        'name'    => 'Active Products',
        'applied' => ['filters' => ['status' => 1]],
    ]);

    expect($filter->src)->toBe('admin::catalog.products')
        ->and($filter->name)->toBe('Active Products');
});

it('casts applied to array', function () {
    $filter = new SavedFilter(['applied' => ['filters' => ['status' => 1]]]);

    expect($filter->applied)->toBeArray()
        ->and($filter->applied['filters']['status'])->toBe(1);
});
