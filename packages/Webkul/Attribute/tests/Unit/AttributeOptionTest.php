<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Attribute\Models\AttributeOption;

it('allows mass assignment of fillable fields', function () {
    $option = new AttributeOption([
        'admin_name'   => 'Blue',
        'swatch_value' => '#0000FF',
        'sort_order'   => 1,
        'attribute_id' => null,
    ]);

    expect($option->admin_name)->toBe('Blue')
        ->and($option->sort_order)->toBe(1);
});

it('has an attribute relationship', function () {
    expect((new AttributeOption)->attribute())->toBeInstanceOf(BelongsTo::class);
});

it('builds a model via factory make', function () {
    $option = AttributeOption::factory()->make();

    expect($option->admin_name)->toBeString();
});
