<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\SocialCommerce\Models\SocialChannelPlatform;

it('allows mass assignment of fillable fields', function () {
    $platform = new SocialChannelPlatform([
        'channel_id' => null,
        'platform'   => 'facebook',
        'is_active'  => true,
        'page_url'   => 'https://facebook.com/mystore',
        'page_id'    => '123456789',
    ]);

    expect($platform->platform)->toBe('facebook')
        ->and($platform->is_active)->toBeTrue()
        ->and($platform->page_url)->toBe('https://facebook.com/mystore');
});

it('casts is_active to boolean', function () {
    $platform = new SocialChannelPlatform(['is_active' => 1]);

    expect($platform->is_active)->toBeTrue();
});

it('casts last_synced_at to datetime when set', function () {
    $platform = new SocialChannelPlatform(['last_synced_at' => '2024-05-01 10:00:00']);

    expect($platform->last_synced_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

it('has a channel relationship', function () {
    expect((new SocialChannelPlatform)->channel())->toBeInstanceOf(BelongsTo::class);
});

it('has many productSyncs relationship', function () {
    expect((new SocialChannelPlatform)->productSyncs())->toBeInstanceOf(HasMany::class);
});

it('has many orders relationship', function () {
    expect((new SocialChannelPlatform)->orders())->toBeInstanceOf(HasMany::class);
});

it('builds a model via factory make', function () {
    $platform = SocialChannelPlatform::factory()->make();

    expect($platform->platform)->toBeIn(['facebook', 'instagram', 'whatsapp']);
});

it('inactive state sets is_active to false', function () {
    $platform = SocialChannelPlatform::factory()->inactive()->make();

    expect($platform->is_active)->toBeFalse();
});
