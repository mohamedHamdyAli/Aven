<?php

use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\DataTransfer\Models\Import;

it('allows mass assignment of fillable fields', function () {
    $import = new Import([
        'state'            => 'pending',
        'import_batch_id'  => null,
        'type'             => 'products',
        'action'           => 'append',
        'validation_strategy' => 'stop_on_errors',
        'allowed_errors'   => 0,
        'field_separator'  => ',',
        'file'             => 'products.csv',
    ]);

    expect($import->type)->toBe('products')
        ->and($import->state)->toBe('pending');
});

it('has many batches relationship', function () {
    expect((new Import)->batches())->toBeInstanceOf(HasMany::class);
});

it('instantiates correctly without factory', function () {
    $import = new Import(['type' => 'products', 'state' => 'pending']);

    expect($import->type)->toBe('products');
});
