<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\SocialLogin\Models\CustomerSocialAccount;

it('allows mass assignment of fillable fields', function () {
    $account = new CustomerSocialAccount([
        'customer_id'   => null,
        'provider_name' => 'google',
        'provider_id'   => '12345678',
    ]);

    expect($account->provider_name)->toBe('google')
        ->and($account->provider_id)->toBe('12345678');
});

it('has a customer relationship', function () {
    expect((new CustomerSocialAccount)->customer())->toBeInstanceOf(BelongsTo::class);
});
