<?php

use Webkul\Marketing\Models\Event;

it('allows mass assignment of fillable fields', function () {
    $event = new Event([
        'name'        => 'Black Friday',
        'description' => 'Annual sale event',
        'date'        => '2025-11-28',
    ]);

    expect($event->name)->toBe('Black Friday')
        ->and($event->date)->toBe('2025-11-28');
});

it('creates a record via factory', function () {
    $event = Event::factory()->create();

    expect($event->exists)->toBeTrue()
        ->and($event->name)->toBeString();
});
