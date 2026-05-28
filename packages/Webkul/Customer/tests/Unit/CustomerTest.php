<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Webkul\Customer\Models\Customer;

it('allows mass assignment of fillable fields', function () {
    $customer = new Customer([
        'first_name'               => 'Ahmed',
        'last_name'                => 'Hassan',
        'email'                    => 'ahmed@example.com',
        'phone'                    => '+201234567890',
        'status'                   => 1,
        'is_verified'              => true,
        'subscribed_to_news_letter' => false,
    ]);

    expect($customer->first_name)->toBe('Ahmed')
        ->and($customer->last_name)->toBe('Hassan')
        ->and($customer->email)->toBe('ahmed@example.com');
});

it('casts subscribed_to_news_letter to boolean', function () {
    $customer = new Customer(['subscribed_to_news_letter' => 1]);

    expect($customer->subscribed_to_news_letter)->toBeTrue();
});

it('has a group relationship', function () {
    expect((new Customer)->group())->toBeInstanceOf(BelongsTo::class);
});

it('has many addresses relationship', function () {
    expect((new Customer)->addresses())->toBeInstanceOf(HasMany::class);
});

it('has one default_address relationship', function () {
    expect((new Customer)->default_address())->toBeInstanceOf(HasOne::class);
});

it('has many orders relationship', function () {
    expect((new Customer)->orders())->toBeInstanceOf(HasMany::class);
});

it('has many wishlist_items relationship', function () {
    expect((new Customer)->wishlist_items())->toBeInstanceOf(HasMany::class);
});

it('creates a record via factory', function () {
    $customer = Customer::factory()->create();

    expect($customer->exists)->toBeTrue()
        ->and($customer->email)->toBeString();
});
