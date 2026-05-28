<?php

use Webkul\Referral\Models\CustomerReferral;

it('allows mass assignment of fillable fields', function () {
    $referral = new CustomerReferral([
        'customer_id'   => null,
        'referral_code' => 'REF-12345',
        'times_used'    => 3,
        'total_earned'  => 150.0000,
    ]);

    expect($referral->referral_code)->toBe('REF-12345')
        ->and($referral->times_used)->toBe(3);
});

it('casts times_used to integer', function () {
    $referral = new CustomerReferral(['times_used' => '5']);

    expect($referral->times_used)->toBeInt();
});

it('casts total_earned to decimal', function () {
    $referral = new CustomerReferral(['total_earned' => '75.5000']);

    expect($referral->total_earned)->toBeNumeric();
});

it('builds a model via factory make', function () {
    $referral = CustomerReferral::factory()->make();

    expect($referral->referral_code)->toStartWith('REF-');
});
