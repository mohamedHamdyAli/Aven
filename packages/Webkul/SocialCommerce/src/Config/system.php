<?php

return [
    [
        'key'  => 'general.content.tiktok_pixel',
        'name' => 'social-commerce::app.configuration.tiktok-pixel.title',
        'info' => 'social-commerce::app.configuration.tiktok-pixel.title-info',
        'sort' => 4,
        'fields' => [
            [
                'name'          => 'enabled',
                'title'         => 'social-commerce::app.configuration.tiktok-pixel.status',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => false,
            ],
            [
                'name'          => 'pixel_id',
                'title'         => 'social-commerce::app.configuration.tiktok-pixel.pixel-id',
                'type'          => 'text',
                'channel_based' => true,
                'locale_based'  => false,
                'depends'       => 'enabled:1',
            ],
        ],
    ],
    [
        'key'  => 'general.content.google_analytics',
        'name' => 'social-commerce::app.configuration.google-analytics.title',
        'info' => 'social-commerce::app.configuration.google-analytics.title-info',
        'sort' => 5,
        'fields' => [
            [
                'name'          => 'enabled',
                'title'         => 'social-commerce::app.configuration.google-analytics.status',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => false,
            ],
            [
                'name'          => 'measurement_id',
                'title'         => 'social-commerce::app.configuration.google-analytics.measurement-id',
                'type'          => 'text',
                'channel_based' => true,
                'locale_based'  => false,
                'depends'       => 'enabled:1',
            ],
        ],
    ],
];
