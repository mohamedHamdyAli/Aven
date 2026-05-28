<?php

use Webkul\PushNotification\Models\PushCampaign;

it('allows mass assignment of fillable fields', function () {
    $campaign = new PushCampaign([
        'title'      => 'New Arrivals',
        'body'       => 'Check out our latest collection!',
        'icon'       => null,
        'url'        => 'https://example.com/new',
        'sent_count' => 0,
    ]);

    expect($campaign->title)->toBe('New Arrivals')
        ->and($campaign->body)->toBe('Check out our latest collection!')
        ->and($campaign->sent_count)->toBe(0);
});

it('creates a record via factory', function () {
    $campaign = PushCampaign::factory()->create();

    expect($campaign->exists)->toBeTrue()
        ->and($campaign->title)->toBeString();
});
