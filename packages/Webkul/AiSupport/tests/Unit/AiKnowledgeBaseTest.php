<?php

use Webkul\AiSupport\Models\AiKnowledgeBase;

it('allows mass assignment of fillable fields', function () {
    $kb = new AiKnowledgeBase([
        'question'   => 'What is your return policy?',
        'answer'     => 'You can return within 30 days.',
        'is_active'  => true,
        'sort_order' => 5,
    ]);

    expect($kb->question)->toBe('What is your return policy?')
        ->and($kb->is_active)->toBeTrue()
        ->and($kb->sort_order)->toBe(5);
});

it('creates a record via factory', function () {
    $kb = AiKnowledgeBase::factory()->create();

    expect($kb->exists)->toBeTrue()
        ->and($kb->is_active)->toBeTrue();
});

it('inactive state sets is_active to false', function () {
    $kb = AiKnowledgeBase::factory()->inactive()->create();

    expect($kb->is_active)->toBeFalse();
});
