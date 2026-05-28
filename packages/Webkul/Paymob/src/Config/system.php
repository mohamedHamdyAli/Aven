<?php

return [
    [
        'key'  => 'sales.payment_methods.paymob',
        'name' => 'Paymob (Egypt)',
        'info' => 'Accept Egyptian card payments via Paymob',
        'sort' => 10,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'Title',
                'type'          => 'text',
                'value'         => 'Pay with Card',
                'channel_based' => false,
                'locale_based'  => true,
            ],
            [
                'name'          => 'description',
                'title'         => 'Description',
                'type'          => 'textarea',
                'channel_based' => false,
                'locale_based'  => true,
            ],
            [
                'name'          => 'api_key',
                'title'         => 'API Key',
                'type'          => 'text',
                'channel_based' => false,
                'locale_based'  => false,
            ],
            [
                'name'          => 'integration_id',
                'title'         => 'Integration ID (Card)',
                'type'          => 'text',
                'channel_based' => false,
                'locale_based'  => false,
            ],
            [
                'name'          => 'iframe_id',
                'title'         => 'iFrame ID',
                'type'          => 'text',
                'channel_based' => false,
                'locale_based'  => false,
            ],
            [
                'name'          => 'hmac_secret',
                'title'         => 'HMAC Secret',
                'type'          => 'text',
                'channel_based' => false,
                'locale_based'  => false,
            ],
            [
                'name'          => 'active',
                'title'         => 'Status',
                'type'          => 'boolean',
                'value'         => 0,
                'channel_based' => false,
                'locale_based'  => false,
            ],
            [
                'name'          => 'sort',
                'title'         => 'Sort Order',
                'type'          => 'text',
                'value'         => 10,
                'channel_based' => false,
                'locale_based'  => false,
            ],
        ],
    ],
];
