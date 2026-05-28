<?php

return [
    'key'  => 'ai-support',
    'name' => 'AI Support',
    'info' => 'Configure AI customer support settings',
    'sort' => 10,
    'icon' => 'icon-setting',

    'children' => [
        'general' => [
            'key'  => 'ai-support.general',
            'name' => 'General Settings',
            'info' => 'AI model and behavior configuration',
            'sort' => 1,

            'fields' => [
                [
                    'name'          => 'api_key',
                    'title'         => 'Groq API Key',
                    'type'          => 'password',
                    'channel_based' => false,
                    'locale_based'  => false,
                    'info'          => 'Get your free API key from console.groq.com',
                ],
                [
                    'name'          => 'model',
                    'title'         => 'AI Model',
                    'type'          => 'select',
                    'channel_based' => false,
                    'locale_based'  => false,
                    'options'       => [
                        ['title' => 'Llama 3.3 70B — Best Quality (Recommended)', 'value' => 'llama-3.3-70b-versatile'],
                        ['title' => 'Llama 3.1 8B — Fastest', 'value' => 'llama-3.1-8b-instant'],
                        ['title' => 'Mixtral 8x7B — Balanced', 'value' => 'mixtral-8x7b-32768'],
                    ],
                    'default' => 'llama-3.3-70b-versatile',
                ],
                [
                    'name'          => 'auto_reply',
                    'title'         => 'Auto Reply (send immediately without review)',
                    'type'          => 'boolean',
                    'channel_based' => false,
                    'locale_based'  => false,
                    'default'       => true,
                ],
                [
                    'name'          => 'system_prompt',
                    'title'         => 'Custom AI Instructions',
                    'type'          => 'textarea',
                    'channel_based' => false,
                    'locale_based'  => false,
                    'info'          => 'Extra instructions for the AI (tone, language, rules, etc.)',
                ],
            ],
        ],

        'channels' => [
            'key'  => 'ai-support.channels',
            'name' => 'Channel Settings',
            'info' => 'Configure WhatsApp, Messenger, and Email channels',
            'sort' => 2,

            'fields' => [
                [
                    'name'          => 'whatsapp_token',
                    'title'         => 'WhatsApp API Token',
                    'type'          => 'password',
                    'channel_based' => false,
                    'locale_based'  => false,
                ],
                [
                    'name'          => 'whatsapp_phone_id',
                    'title'         => 'WhatsApp Phone Number ID',
                    'type'          => 'text',
                    'channel_based' => false,
                    'locale_based'  => false,
                ],
                [
                    'name'          => 'whatsapp_verify_token',
                    'title'         => 'WhatsApp Webhook Verify Token',
                    'type'          => 'text',
                    'channel_based' => false,
                    'locale_based'  => false,
                    'info'          => 'Set this in your Meta App webhook configuration',
                ],
                [
                    'name'          => 'messenger_token',
                    'title'         => 'Messenger Page Access Token',
                    'type'          => 'password',
                    'channel_based' => false,
                    'locale_based'  => false,
                ],
                [
                    'name'          => 'messenger_verify_token',
                    'title'         => 'Messenger Webhook Verify Token',
                    'type'          => 'text',
                    'channel_based' => false,
                    'locale_based'  => false,
                ],
                [
                    'name'          => 'email_address',
                    'title'         => 'Support Email Address',
                    'type'          => 'email',
                    'channel_based' => false,
                    'locale_based'  => false,
                    'info'          => 'Email address used to send replies to customers',
                ],
            ],
        ],
    ],
];
