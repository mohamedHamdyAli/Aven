<?php

use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\AiSupport\Models\AiConversation;

it('allows mass assignment of fillable fields', function () {
    $conv = new AiConversation([
        'channel'            => 'web',
        'channel_identifier' => 'abc-123',
        'customer_id'        => null,
        'status'             => 'open',
        'assigned_admin_id'  => null,
    ]);

    expect($conv->channel)->toBe('web')
        ->and($conv->status)->toBe('open');
});

it('has many messages relationship', function () {
    expect((new AiConversation)->messages())->toBeInstanceOf(HasMany::class);
});

it('open state creates open conversation', function () {
    $conv = AiConversation::factory()->open()->create();

    expect($conv->status)->toBe('open');
});

it('handedOff state creates human_handoff conversation', function () {
    $conv = AiConversation::factory()->handedOff()->create();

    expect($conv->status)->toBe('human_handoff');
});

it('closed state creates closed conversation', function () {
    $conv = AiConversation::factory()->closed()->create();

    expect($conv->status)->toBe('closed');
});

it('creates a record via factory', function () {
    $conv = AiConversation::factory()->create();

    expect($conv->exists)->toBeTrue()
        ->and($conv->channel)->toBeIn(['web', 'whatsapp', 'messenger']);
});
