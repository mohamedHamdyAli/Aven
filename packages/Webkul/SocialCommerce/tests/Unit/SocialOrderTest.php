<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\SocialCommerce\Models\SocialOrder;

it('allows mass assignment of fillable fields', function () {
    $order = new SocialOrder([
        'social_channel_platform_id' => null,
        'order_id'                   => null,
        'external_order_id'          => 'EXT-001',
        'platform_data'              => ['key' => 'value'],
        'sync_status'                => 'synced',
        'error_message'              => null,
    ]);

    expect($order->external_order_id)->toBe('EXT-001')
        ->and($order->sync_status)->toBe('synced');
});

it('casts platform_data to array', function () {
    $order = new SocialOrder(['platform_data' => ['id' => 123]]);

    expect($order->platform_data)->toBeArray()
        ->and($order->platform_data['id'])->toBe(123);
});

it('has a platform relationship', function () {
    expect((new SocialOrder)->platform())->toBeInstanceOf(BelongsTo::class);
});

it('has an order relationship', function () {
    expect((new SocialOrder)->order())->toBeInstanceOf(BelongsTo::class);
});

it('builds a model via factory make', function () {
    $order = SocialOrder::factory()->make();

    expect($order->sync_status)->toBe('synced');
});

it('failed state sets sync_status to failed', function () {
    $order = SocialOrder::factory()->failed()->make();

    expect($order->sync_status)->toBe('failed')
        ->and($order->error_message)->not()->toBeNull();
});
