<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\CostManagement\Models\ProfitDistribution;
use Webkul\CostManagement\Models\ProfitDistributionItem;
use Webkul\CostManagement\Models\Shareholder;

it('allows mass assignment of fillable fields', function () {
    $item = new ProfitDistributionItem([
        'distribution_id' => null,
        'shareholder_id'  => null,
        'percentage'      => 40.0,
        'amount'          => 20000.00,
    ]);

    expect($item->percentage)->toBe(40.0)
        ->and($item->amount)->toBe(20000.00);
});

it('casts percentage and amount to float', function () {
    $item = new ProfitDistributionItem(['percentage' => '25', 'amount' => '5000']);

    expect($item->percentage)->toBeFloat()
        ->and($item->amount)->toBeFloat();
});

it('has a shareholder relationship', function () {
    expect((new ProfitDistributionItem)->shareholder())->toBeInstanceOf(BelongsTo::class);
});

it('has a distribution relationship', function () {
    expect((new ProfitDistributionItem)->distribution())->toBeInstanceOf(BelongsTo::class);
});

it('creates a record via factory', function () {
    $distribution = ProfitDistribution::factory()->create();
    $shareholder = Shareholder::factory()->create();

    $item = ProfitDistributionItem::factory()->create([
        'distribution_id' => $distribution->id,
        'shareholder_id'  => $shareholder->id,
    ]);

    expect($item->exists)->toBeTrue()
        ->and($item->distribution_id)->toBe($distribution->id)
        ->and($item->shareholder_id)->toBe($shareholder->id);
});
