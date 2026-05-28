<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\GDPR\Models\GDPRDataRequest;

it('allows mass assignment of fillable fields', function () {
    $request = new GDPRDataRequest([
        'customer_id' => null,
        'email'       => 'user@example.com',
        'status'      => 'pending',
        'type'        => 'delete',
        'message'     => 'Please delete my data',
        'revoked_at'  => null,
    ]);

    expect($request->email)->toBe('user@example.com')
        ->and($request->status)->toBe('pending')
        ->and($request->type)->toBe('delete');
});

it('has a customer relationship', function () {
    expect((new GDPRDataRequest)->customer())->toBeInstanceOf(BelongsTo::class);
});
