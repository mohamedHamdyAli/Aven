<?php

use Webkul\CostManagement\Models\FinancialTransaction;

it('allows mass assignment of fillable fields', function () {
    $tx = new FinancialTransaction([
        'type'             => 'sale',
        'amount'           => 1500.00,
        'description'      => 'Online sale',
        'platform'         => 'Facebook',
        'reference_id'     => null,
        'reference_type'   => null,
        'transaction_date' => '2024-05-15',
    ]);

    expect($tx->type)->toBe('sale')
        ->and($tx->amount)->toBe(1500.00)
        ->and($tx->platform)->toBe('Facebook');
});

it('casts amount to float', function () {
    $tx = new FinancialTransaction(['amount' => '999']);

    expect($tx->amount)->toBeFloat();
});

it('casts transaction_date to date', function () {
    $tx = new FinancialTransaction(['transaction_date' => '2024-05-15']);

    expect($tx->transaction_date)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

it('typeLabels static method returns all expected types', function () {
    $labels = FinancialTransaction::typeLabels();

    expect($labels)->toHaveKey('sale')
        ->and($labels)->toHaveKey('refund')
        ->and($labels)->toHaveKey('expense')
        ->and($labels)->toHaveKey('ad_spend');
});

it('platforms static method returns array of platforms', function () {
    $platforms = FinancialTransaction::platforms();

    expect($platforms)->toBeArray()
        ->and($platforms)->toContain('Facebook')
        ->and($platforms)->toContain('Google');
});

it('creates a record via factory', function () {
    $tx = FinancialTransaction::factory()->create();

    expect($tx->exists)->toBeTrue()
        ->and($tx->type)->toBeIn(['sale', 'refund', 'expense', 'ad_spend']);
});
