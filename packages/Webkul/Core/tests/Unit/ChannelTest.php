<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Webkul\Core\Models\Channel;

it('allows mass assignment of fillable fields', function () {
    $channel = new Channel([
        'code'              => 'default',
        'name'              => 'Default Store',
        'description'       => null,
        'theme'             => null,
        'hostname'          => 'aven.test',
        'default_locale_id' => 1,
        'base_currency_id'  => 1,
        'root_category_id'  => 1,
        'is_maintenance_on' => false,
    ]);

    expect($channel->code)->toBe('default')
        ->and($channel->hostname)->toBe('aven.test');
});

it('casts home_seo to array', function () {
    $channel = new Channel(['home_seo' => ['title' => 'Home']]);

    expect($channel->home_seo)->toBeArray()
        ->and($channel->home_seo['title'])->toBe('Home');
});

it('has a default_locale relationship', function () {
    expect((new Channel)->default_locale())->toBeInstanceOf(BelongsTo::class);
});

it('has a base_currency relationship', function () {
    expect((new Channel)->base_currency())->toBeInstanceOf(BelongsTo::class);
});

it('has many locales relationship', function () {
    expect((new Channel)->locales())->toBeInstanceOf(BelongsToMany::class);
});

it('has many currencies relationship', function () {
    expect((new Channel)->currencies())->toBeInstanceOf(BelongsToMany::class);
});

it('creates a record via factory', function () {
    $channel = Channel::factory()->create();

    expect($channel->exists)->toBeTrue()
        ->and($channel->code)->toBeString();
});
