<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Notification\Models\Notification;

it('allows mass assignment of fillable fields', function () {
    $notification = new Notification([
        'type'     => 'new_order',
        'read'     => false,
        'order_id' => null,
    ]);

    expect($notification->type)->toBe('new_order')
        ->and($notification->read)->toBeFalse();
});

it('has an order relationship', function () {
    expect((new Notification)->order())->toBeInstanceOf(BelongsTo::class);
});
