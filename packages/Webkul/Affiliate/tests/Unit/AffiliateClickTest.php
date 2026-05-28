<?php

use Webkul\Affiliate\Models\AffiliateClick;

it('allows mass assignment of fillable fields', function () {
    $click = new AffiliateClick([
        'affiliate_id' => 1,
        'ip'           => '192.168.1.1',
        'url'          => 'https://example.com/ref/CODE',
    ]);

    expect($click->ip)->toBe('192.168.1.1')
        ->and($click->url)->toBe('https://example.com/ref/CODE');
});

it('creates a record via factory', function () {
    $affiliate = \Webkul\Affiliate\Models\Affiliate::factory()->create();
    $click = AffiliateClick::factory()->create(['affiliate_id' => $affiliate->id]);

    expect($click->exists)->toBeTrue()
        ->and($click->affiliate_id)->toBe($affiliate->id);
});
