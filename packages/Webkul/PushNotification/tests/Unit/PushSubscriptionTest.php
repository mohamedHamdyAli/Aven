<?php

use Webkul\PushNotification\Models\PushSubscription;

it('allows mass assignment of fillable fields', function () {
    $sub = new PushSubscription([
        'customer_id' => null,
        'endpoint'    => 'https://fcm.googleapis.com/fcm/send/abc123',
        'p256dh'      => 'key123',
        'auth'        => 'auth456',
    ]);

    expect($sub->endpoint)->toBe('https://fcm.googleapis.com/fcm/send/abc123')
        ->and($sub->p256dh)->toBe('key123');
});

it('creates a record via factory', function () {
    $sub = PushSubscription::factory()->create();

    expect($sub->exists)->toBeTrue()
        ->and($sub->endpoint)->toStartWith('https://');
});
