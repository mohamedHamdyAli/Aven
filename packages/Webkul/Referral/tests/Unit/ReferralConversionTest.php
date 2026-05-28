<?php

use Webkul\Referral\Models\ReferralConversion;

it('allows mass assignment of fillable fields', function () {
    $conversion = new ReferralConversion([
        'referral_code'          => 'REF-12345',
        'referrer_customer_id'   => null,
        'referred_customer_id'   => null,
        'referred_email'         => 'new@example.com',
        'order_id'               => null,
        'status'                 => 'pending',
        'rewarded_at'            => null,
    ]);

    expect($conversion->referral_code)->toBe('REF-12345')
        ->and($conversion->status)->toBe('pending')
        ->and($conversion->referred_email)->toBe('new@example.com');
});

it('casts rewarded_at to datetime when set', function () {
    $conversion = new ReferralConversion(['rewarded_at' => '2024-05-01 10:00:00']);

    expect($conversion->rewarded_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

it('builds a model via factory make', function () {
    $conversion = ReferralConversion::factory()->make();

    expect($conversion->status)->toBe('pending');
});

it('rewarded state sets status and rewarded_at', function () {
    $conversion = ReferralConversion::factory()->rewarded()->make();

    expect($conversion->status)->toBe('rewarded')
        ->and($conversion->rewarded_at)->not()->toBeNull();
});
