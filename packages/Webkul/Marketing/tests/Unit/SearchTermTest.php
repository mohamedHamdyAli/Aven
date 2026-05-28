<?php

use Webkul\Marketing\Models\SearchTerm;

it('allows mass assignment of fillable fields', function () {
    $term = new SearchTerm([
        'term'                        => 'blue sneakers',
        'results'                     => 10,
        'uses'                        => 5,
        'redirect_url'                => null,
        'display_in_suggested_terms'  => true,
        'locale'                      => 'en',
        'channel_id'                  => null,
    ]);

    expect($term->term)->toBe('blue sneakers')
        ->and($term->results)->toBe(10);
});

it('creates a record via factory', function () {
    $term = SearchTerm::factory()->create();

    expect($term->exists)->toBeTrue()
        ->and($term->term)->toBeString();
});
