<?php

use Webkul\SizeGuide\Models\SizeChart;
use Webkul\SizeGuide\Models\SizeChartRow;

it('allows mass assignment of fillable fields', function () {
    $row = new SizeChartRow([
        'size_chart_id' => 1,
        'label'         => 'M',
        'sort_order'    => 2,
        'eu_size'       => '38',
        'uk_size'       => '10',
        'us_size'       => '8',
        'chest_min'     => 88.0,
        'chest_max'     => 92.0,
        'waist_min'     => 70.0,
        'waist_max'     => 74.0,
    ]);

    expect($row->label)->toBe('M')
        ->and($row->eu_size)->toBe('38')
        ->and($row->chest_min)->toBe(88.0);
});

it('casts numeric fields to float', function () {
    $row = new SizeChartRow(['chest_min' => '90', 'waist_min' => '68']);

    expect($row->chest_min)->toBeFloat()
        ->and($row->waist_min)->toBeFloat();
});

it('range method returns formatted range string', function () {
    $row = new SizeChartRow(['chest_min' => 88.0, 'chest_max' => 92.0]);

    expect($row->range('chest'))->toBe('88-92');
});

it('range method returns single value when min equals max', function () {
    $row = new SizeChartRow(['chest_min' => 90.0, 'chest_max' => 90.0]);

    expect($row->range('chest'))->toBe('90');
});

it('range method returns null when min is null', function () {
    $row = new SizeChartRow(['chest_min' => null, 'chest_max' => null]);

    expect($row->range('chest'))->toBeNull();
});

it('toIn converts cm to inches', function () {
    expect(SizeChartRow::toIn(25.4))->toBe(10.0);
});

it('creates a record via factory', function () {
    $chart = SizeChart::factory()->create();
    $row = SizeChartRow::factory()->create(['size_chart_id' => $chart->id]);

    expect($row->exists)->toBeTrue()
        ->and($row->label)->toBeIn(['XS', 'S', 'M', 'L', 'XL', 'XXL']);
});
