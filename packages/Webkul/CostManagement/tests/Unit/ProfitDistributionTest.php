<?php

use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\CostManagement\Models\ProfitDistribution;

it('allows mass assignment of fillable fields', function () {
    $dist = new ProfitDistribution([
        'period_from'       => '2024-01-01',
        'period_to'         => '2024-03-31',
        'net_profit'        => 50000.00,
        'total_distributed' => 45000.00,
        'notes'             => 'Q1 2024',
    ]);

    expect($dist->net_profit)->toBe(50000.00)
        ->and($dist->total_distributed)->toBe(45000.00)
        ->and($dist->notes)->toBe('Q1 2024');
});

it('casts period_from and period_to to date', function () {
    $dist = new ProfitDistribution(['period_from' => '2024-01-01', 'period_to' => '2024-03-31']);

    expect($dist->period_from)->toBeInstanceOf(\Illuminate\Support\Carbon::class)
        ->and($dist->period_to)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

it('casts net_profit and total_distributed to float', function () {
    $dist = new ProfitDistribution(['net_profit' => '10000', 'total_distributed' => '9000']);

    expect($dist->net_profit)->toBeFloat()
        ->and($dist->total_distributed)->toBeFloat();
});

it('has many items relationship', function () {
    expect((new ProfitDistribution)->items())->toBeInstanceOf(HasMany::class);
});

it('creates a record via factory', function () {
    $dist = ProfitDistribution::factory()->create();

    expect($dist->exists)->toBeTrue()
        ->and($dist->net_profit)->toBeFloat();
});
