<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\SocialCommerce\Models\SocialProductSync;

it('allows mass assignment of fillable fields', function () {
    $sync = new SocialProductSync([
        'social_channel_platform_id' => null,
        'product_id'                 => null,
        'external_product_id'        => 'FB-123456',
        'sync_status'                => 'synced',
        'error_message'              => null,
        'synced_at'                  => now()->toDateTimeString(),
    ]);

    expect($sync->external_product_id)->toBe('FB-123456')
        ->and($sync->sync_status)->toBe('synced');
});

it('casts synced_at to datetime', function () {
    $sync = new SocialProductSync(['synced_at' => '2024-05-01 10:00:00']);

    expect($sync->synced_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

it('has a platform relationship', function () {
    expect((new SocialProductSync)->platform())->toBeInstanceOf(BelongsTo::class);
});

it('has a product relationship', function () {
    expect((new SocialProductSync)->product())->toBeInstanceOf(BelongsTo::class);
});

it('builds a model via factory make', function () {
    $sync = SocialProductSync::factory()->make();

    expect($sync->sync_status)->toBe('synced');
});

it('failed state sets sync_status to failed', function () {
    $sync = SocialProductSync::factory()->failed()->make();

    expect($sync->sync_status)->toBe('failed')
        ->and($sync->synced_at)->toBeNull();
});
