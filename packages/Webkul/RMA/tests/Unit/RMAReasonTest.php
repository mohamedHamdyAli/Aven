<?php

use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\RMA\Models\RMAReason;

it('allows mass assignment of fillable fields', function () {
    $reason = new RMAReason([
        'title'    => 'Product defective',
        'status'   => 1,
        'position' => 1,
    ]);

    expect($reason->title)->toBe('Product defective')
        ->and($reason->status)->toBe(1);
});

it('has many reasonResolutions relationship', function () {
    expect((new RMAReason)->reasonResolutions())->toBeInstanceOf(HasMany::class);
});
