<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Core\Models\SubscribersList;

it('allows mass assignment of fillable fields', function () {
    $subscriber = new SubscribersList([
        'email'         => 'user@example.com',
        'is_subscribed' => true,
        'customer_id'   => null,
        'channel_id'    => null,
    ]);

    expect($subscriber->email)->toBe('user@example.com')
        ->and($subscriber->is_subscribed)->toBeTrue();
});

it('has a customer relationship', function () {
    expect((new SubscribersList)->customer())->toBeInstanceOf(BelongsTo::class);
});

it('creates a record via factory', function () {
    $subscriber = SubscribersList::factory()->create();

    expect($subscriber->exists)->toBeTrue()
        ->and($subscriber->email)->toBeString();
});
