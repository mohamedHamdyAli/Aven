<?php

use Webkul\Affiliate\Models\Affiliate;

it('allows mass assignment of fillable fields', function () {
    $affiliate = new Affiliate([
        'customer_id'     => null,
        'name'            => 'John Doe',
        'email'           => 'john@example.com',
        'code'            => 'JOHN123',
        'status'          => 'active',
        'commission_rate' => 10.5,
        'total_earned'    => 0,
        'total_paid'      => 0,
        'notes'           => null,
    ]);

    expect($affiliate->name)->toBe('John Doe')
        ->and($affiliate->code)->toBe('JOHN123')
        ->and($affiliate->status)->toBe('active');
});

it('creates a record via factory', function () {
    $affiliate = Affiliate::factory()->create();

    expect($affiliate->exists)->toBeTrue()
        ->and($affiliate->status)->toBe('active')
        ->and($affiliate->commission_rate)->toBeNumeric();
});

it('code is unique per factory record', function () {
    $a = Affiliate::factory()->create();
    $b = Affiliate::factory()->create();

    expect($a->code)->not()->toBe($b->code);
});
