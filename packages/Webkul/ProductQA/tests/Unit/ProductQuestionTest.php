<?php

use Webkul\ProductQA\Models\ProductQuestion;

it('allows mass assignment of fillable fields', function () {
    $q = new ProductQuestion([
        'product_id'     => null,
        'customer_id'    => null,
        'customer_name'  => 'Alice',
        'customer_email' => 'alice@example.com',
        'question'       => 'Does this come in blue?',
        'answer'         => 'Yes, we have it in blue.',
        'status'         => 'approved',
        'is_published'   => true,
    ]);

    expect($q->customer_name)->toBe('Alice')
        ->and($q->question)->toBe('Does this come in blue?')
        ->and($q->status)->toBe('approved');
});

it('casts is_published to boolean', function () {
    $q = new ProductQuestion(['is_published' => 1]);

    expect($q->is_published)->toBeTrue();
});

it('scope published filters by status and is_published via query builder', function () {
    $query = ProductQuestion::published();

    expect($query->toSql())->toContain('status')
        ->and($query->toSql())->toContain('is_published')
        ->and($query->getBindings())->toContain('approved');
});

it('builds a model via factory make', function () {
    $q = ProductQuestion::factory()->make();

    expect($q->status)->toBe('approved');
});

it('pending state creates pending question', function () {
    $q = ProductQuestion::factory()->pending()->make();

    expect($q->status)->toBe('pending')
        ->and($q->is_published)->toBeFalse();
});
