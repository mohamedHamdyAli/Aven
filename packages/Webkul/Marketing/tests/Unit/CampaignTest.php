<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Marketing\Models\Campaign;

it('allows mass assignment of fillable fields', function () {
    $campaign = new Campaign([
        'name'                   => 'Spring Sale',
        'subject'                => 'Spring Sale is here!',
        'status'                 => 1,
        'channel_id'             => null,
        'customer_group_id'      => null,
        'marketing_template_id'  => null,
        'spooling'               => 'now',
        'marketing_event_id'     => null,
    ]);

    expect($campaign->name)->toBe('Spring Sale')
        ->and($campaign->subject)->toBe('Spring Sale is here!');
});

it('has an event relationship', function () {
    expect((new Campaign)->event())->toBeInstanceOf(BelongsTo::class);
});

it('has a channel relationship', function () {
    expect((new Campaign)->channel())->toBeInstanceOf(BelongsTo::class);
});

it('builds a model via factory make', function () {
    $campaign = Campaign::factory()->make();

    expect($campaign->name)->toBeString()
        ->and($campaign->subject)->toBeString();
});
