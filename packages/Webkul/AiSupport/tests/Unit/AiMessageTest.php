<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\AiSupport\Models\AiConversation;
use Webkul\AiSupport\Models\AiMessage;

it('allows mass assignment of fillable fields', function () {
    $message = new AiMessage([
        'conversation_id' => null,
        'role'            => 'user',
        'content'         => 'Hello!',
        'ai_draft'        => null,
        'status'          => 'sent',
        'sent_at'         => now()->toDateTimeString(),
    ]);

    expect($message->role)->toBe('user')
        ->and($message->content)->toBe('Hello!')
        ->and($message->status)->toBe('sent');
});

it('casts sent_at to datetime', function () {
    $message = new AiMessage(['sent_at' => '2024-01-15 10:00:00']);

    expect($message->sent_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

it('has a conversation relationship', function () {
    expect((new AiMessage)->conversation())->toBeInstanceOf(BelongsTo::class);
});

it('creates a record via factory with conversation', function () {
    $conversation = AiConversation::factory()->create();
    $message = AiMessage::factory()->create(['conversation_id' => $conversation->id]);

    expect($message->exists)->toBeTrue()
        ->and($message->conversation_id)->toBe($conversation->id)
        ->and($message->role)->toBeIn(['user', 'assistant', 'system']);
});
