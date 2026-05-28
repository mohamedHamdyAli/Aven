<?php

use Webkul\Core\Models\CoreConfig;

it('allows mass assignment of fillable fields', function () {
    $config = new CoreConfig([
        'code'         => 'general.locale.default',
        'value'        => 'en',
        'channel_code' => 'default',
        'locale_code'  => 'en',
    ]);

    expect($config->code)->toBe('general.locale.default')
        ->and($config->value)->toBe('en');
});

it('instantiates with correct code', function () {
    $config = new CoreConfig(['code' => 'general.locale.default', 'value' => 'en']);

    expect($config->code)->toBe('general.locale.default');
});
