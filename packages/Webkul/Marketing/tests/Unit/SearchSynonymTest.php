<?php

use Webkul\Marketing\Models\SearchSynonym;

it('allows mass assignment of fillable fields', function () {
    $synonym = new SearchSynonym([
        'name'  => 'Footwear Synonyms',
        'terms' => 'shoes,sneakers,footwear',
    ]);

    expect($synonym->name)->toBe('Footwear Synonyms')
        ->and($synonym->terms)->toBe('shoes,sneakers,footwear');
});

it('creates a record via factory', function () {
    $synonym = SearchSynonym::factory()->create();

    expect($synonym->exists)->toBeTrue()
        ->and($synonym->name)->toBeString();
});
