<?php

return [
    'configuration' => [
        'tiktok-pixel' => [
            'title'      => 'TikTok Pixel',
            'title-info' => 'Add TikTok Pixel tracking to your storefront',
            'status'     => 'Status',
            'pixel-id'   => 'Pixel ID',
        ],
        'google-analytics' => [
            'title'          => 'Google Analytics (YouTube)',
            'title-info'     => 'Add Google Analytics 4 tracking for YouTube Shopping',
            'status'         => 'Status',
            'measurement-id' => 'Measurement ID (G-XXXXXXXXXX)',
        ],
    ],

    'admin' => [
        'layouts' => [
            'sidebar' => [
                'social-commerce' => 'Social Commerce',
            ],
        ],

        'acl' => [
            'social-commerce' => 'Social Commerce',
            'channels'        => 'Channels',
        ],

        'social-channels' => [
            'index' => [
                'title'      => 'Social Commerce Channels',
                'create-btn' => 'Add Channel',
                'datagrid'   => [
                    'id'          => '#',
                    'channel'     => 'Channel',
                    'platform'    => 'Platform',
                    'status'      => 'Status',
                    'last-synced' => 'Last Synced',
                    'sync'        => 'Sync Products',
                ],
            ],
            'create' => [
                'title'           => 'Add Social Commerce Channel',
                'save-btn'        => 'Save Channel',
                'general'         => 'General',
                'channel'         => 'Store Channel',
                'platform'        => 'Social Platform',
                'page-url'        => 'Page / Profile URL',
                'page-id'         => 'Page / Store ID',
                'status'          => 'Active',
                'tracking'        => 'Tracking',
                'pixel-id'        => 'Pixel ID',
                'api-credentials' => 'API Credentials',
                'app-id'          => 'App ID / Business ID',
                'app-secret'      => 'App Secret',
                'access-token'    => 'Access Token',
                'catalog-id'      => 'Catalog ID',
                'phone-number-id' => 'WhatsApp Phone Number ID',
                'success'         => 'Social channel created successfully.',
            ],
            'edit' => [
                'title'       => 'Edit Social Commerce Channel',
                'save-btn'    => 'Update Channel',
                'leave-blank' => 'Leave blank to keep current value',
                'success'     => 'Social channel updated successfully.',
            ],
            'delete' => [
                'success' => 'Social channel deleted successfully.',
            ],
            'sync' => [
                'queued' => 'Product sync has been queued.',
            ],
        ],
    ],
];
