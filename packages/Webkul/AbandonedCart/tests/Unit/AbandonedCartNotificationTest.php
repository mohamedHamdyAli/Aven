<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\AbandonedCart\Models\AbandonedCartNotification;

it('allows mass assignment of fillable fields', function () {
    $data = [
        'cart_id'        => null,
        'channel'        => 'email',
        'attempt_number' => 1,
        'status'         => 'sent',
        'sent_at'        => now()->toDateTimeString(),
        'opened_at'      => null,
        'clicked_at'     => null,
        'error_message'  => null,
    ];

    $notification = new AbandonedCartNotification($data);

    expect($notification->channel)->toBe('email')
        ->and($notification->attempt_number)->toBe(1)
        ->and($notification->status)->toBe('sent');
});

it('casts sent_at to datetime', function () {
    $notification = new AbandonedCartNotification(['sent_at' => '2024-01-15 10:00:00']);

    expect($notification->sent_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

it('casts opened_at to datetime when set', function () {
    $notification = new AbandonedCartNotification(['opened_at' => '2024-01-15 12:00:00']);

    expect($notification->opened_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

it('casts clicked_at to datetime when set', function () {
    $notification = new AbandonedCartNotification(['clicked_at' => '2024-01-15 13:00:00']);

    expect($notification->clicked_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

it('has a cart relationship', function () {
    expect((new AbandonedCartNotification)->cart())->toBeInstanceOf(BelongsTo::class);
});

it('disables automatic timestamps', function () {
    expect((new AbandonedCartNotification)->timestamps)->toBeFalse();
});

it('builds a model via factory make', function () {
    $notification = AbandonedCartNotification::factory()->make([
        'channel'        => 'whatsapp',
        'attempt_number' => 2,
        'status'         => 'sent',
    ]);

    expect($notification->channel)->toBe('whatsapp')
        ->and($notification->attempt_number)->toBe(2)
        ->and($notification->status)->toBe('sent');
});
