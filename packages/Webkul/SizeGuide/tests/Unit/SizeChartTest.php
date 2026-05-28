<?php

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\SizeGuide\Models\SizeChart;

it('allows mass assignment of fillable fields', function () {
    $chart = new SizeChart([
        'name'           => 'Women Tops Chart',
        'gender'         => 'women',
        'type'           => 'clothing',
        'image'          => null,
        'image_overlays' => [],
        'column_headers' => ['EU', 'UK', 'US'],
    ]);

    expect($chart->name)->toBe('Women Tops Chart')
        ->and($chart->gender)->toBe('women')
        ->and($chart->type)->toBe('clothing');
});

it('casts image_overlays to array', function () {
    $chart = new SizeChart(['image_overlays' => ['chest', 'waist']]);

    expect($chart->image_overlays)->toBeArray();
});

it('casts column_headers to array', function () {
    $chart = new SizeChart(['column_headers' => ['EU', 'UK']]);

    expect($chart->column_headers)->toBeArray()
        ->and($chart->column_headers)->toContain('EU');
});

it('has many rows relationship', function () {
    expect((new SizeChart)->rows())->toBeInstanceOf(HasMany::class);
});

it('has many products relationship', function () {
    expect((new SizeChart)->products())->toBeInstanceOf(BelongsToMany::class);
});

it('creates a record via factory', function () {
    $chart = SizeChart::factory()->create();

    expect($chart->exists)->toBeTrue()
        ->and($chart->gender)->toBeIn(['men', 'women', 'kids', 'unisex']);
});
