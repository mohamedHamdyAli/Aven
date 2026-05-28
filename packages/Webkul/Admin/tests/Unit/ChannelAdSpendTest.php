<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Admin\Models\ChannelAdSpend;

it('allows mass assignment of fillable fields', function () {
    $spend = new ChannelAdSpend([
        'channel_id' => null,
        'amount'     => 1500.00,
        'start_date' => '2025-01-01',
        'end_date'   => '2025-01-31',
        'source'     => 'google',
        'notes'      => 'January campaign',
    ]);

    expect($spend->source)->toBe('google')
        ->and($spend->notes)->toBe('January campaign');
});

it('casts amount to decimal', function () {
    $spend = new ChannelAdSpend(['amount' => '1500.5000']);

    expect($spend->amount)->toBeNumeric();
});

it('casts start_date and end_date to date', function () {
    $spend = new ChannelAdSpend([
        'start_date' => '2025-01-01',
        'end_date'   => '2025-01-31',
    ]);

    expect($spend->start_date)->toBeInstanceOf(\Illuminate\Support\Carbon::class)
        ->and($spend->end_date)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

it('has a channel relationship', function () {
    expect((new ChannelAdSpend)->channel())->toBeInstanceOf(BelongsTo::class);
});
