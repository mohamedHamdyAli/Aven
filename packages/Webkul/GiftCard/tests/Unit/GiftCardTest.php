<?php

use Webkul\GiftCard\Models\GiftCard;

it('allows mass assignment of fillable fields', function () {
    $card = new GiftCard([
        'code'            => 'ABCD-EFGH-IJKL',
        'initial_balance' => 200.00,
        'used_amount'     => 0,
        'is_active'       => true,
        'recipient_email' => 'gift@example.com',
        'recipient_name'  => 'Jane Doe',
        'expires_at'      => '2025-12-31',
        'message'         => 'Happy Birthday!',
    ]);

    expect($card->code)->toBe('ABCD-EFGH-IJKL')
        ->and($card->initial_balance)->toBe(200.00)
        ->and($card->is_active)->toBeTrue();
});

it('casts initial_balance and used_amount to float', function () {
    $card = new GiftCard(['initial_balance' => '150.00', 'used_amount' => '50.00']);

    expect($card->initial_balance)->toBeFloat()
        ->and($card->used_amount)->toBeFloat();
});

it('casts is_active to boolean', function () {
    $card = new GiftCard(['is_active' => 1]);

    expect($card->is_active)->toBeTrue();
});

it('casts expires_at to date', function () {
    $card = new GiftCard(['expires_at' => '2025-12-31']);

    expect($card->expires_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

it('remaining_balance accessor returns initial minus used', function () {
    $card = new GiftCard(['initial_balance' => 200.00, 'used_amount' => 50.00]);

    expect($card->remaining_balance)->toBe(150.00);
});

it('remaining_balance cannot go below zero', function () {
    $card = new GiftCard(['initial_balance' => 100.00, 'used_amount' => 150.00]);

    expect($card->remaining_balance)->toBe(0.0);
});

it('isUsable returns true for active card with balance and future expiry', function () {
    $card = GiftCard::factory()->create([
        'is_active'       => true,
        'initial_balance' => 100.00,
        'used_amount'     => 0,
        'expires_at'      => now()->addYear(),
    ]);

    expect($card->isUsable())->toBeTrue();
});

it('isUsable returns false for inactive card', function () {
    $card = GiftCard::factory()->create(['is_active' => false]);

    expect($card->isUsable())->toBeFalse();
});

it('isUsable returns false for expired card', function () {
    $card = GiftCard::factory()->expired()->create(['is_active' => true, 'used_amount' => 0]);

    expect($card->isUsable())->toBeFalse();
});

it('isUsable returns false for fully used card', function () {
    $card = GiftCard::factory()->fullyUsed()->create(['is_active' => true]);

    expect($card->isUsable())->toBeFalse();
});

it('generateCode returns formatted string', function () {
    $code = GiftCard::generateCode();

    expect($code)->toBeString()
        ->and(strlen($code))->toBe(14);
});

it('creates a record via factory', function () {
    $card = GiftCard::factory()->create();

    expect($card->exists)->toBeTrue()
        ->and($card->is_active)->toBeTrue();
});
