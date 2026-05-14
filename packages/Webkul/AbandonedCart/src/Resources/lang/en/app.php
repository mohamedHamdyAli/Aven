<?php

return [
    'configuration' => [
        'index' => [
            'sales' => [
                'abandoned-cart' => [
                    'title' => 'Abandoned Cart Recovery',
                    'info'  => 'Configure automated recovery campaigns for abandoned shopping carts.',

                    'general' => [
                        'title'             => 'General Settings',
                        'enabled'           => 'Enable Abandoned Cart Recovery',
                        'inactivity-threshold' => 'Inactivity Threshold (minutes)',
                        'max-attempts'      => 'Maximum Notification Attempts',
                        'attempt-1-delay'   => 'Delay Before Attempt 1 (hours)',
                        'attempt-2-delay'   => 'Delay Before Attempt 2 (hours)',
                        'attempt-3-delay'   => 'Delay Before Attempt 3 (hours)',
                        'auto-cancel'       => 'Auto-Cancel After Max Attempts',
                        'require-approval'  => 'Require Admin Approval to Cancel',
                    ],

                    'channels' => [
                        'title'            => 'Notification Channels',
                        'email-enabled'    => 'Enable Email Notifications',
                        'whatsapp-enabled' => 'Enable WhatsApp Notifications',
                        'whatsapp-token'   => 'WhatsApp Cloud API Token',
                        'whatsapp-phone-id' => 'WhatsApp Phone Number ID',
                        'messenger-enabled' => 'Enable Facebook Messenger Notifications',
                        'messenger-token'  => 'Messenger Page Access Token',
                        'messenger-page-id' => 'Messenger Page ID',
                    ],

                    'fraud' => [
                        'title'            => 'Fraud Prevention',
                        'enabled'          => 'Enable Fraud Detection',
                        'threshold'        => 'Auto-Flag Score Threshold (0–100)',
                        'block-disposable' => 'Block Disposable Email Domains',
                        'velocity-window'  => 'Velocity Check Window (minutes)',
                        'velocity-max'     => 'Max Orders in Velocity Window',
                    ],
                ],
            ],
        ],
    ],

    'admin' => [
        'title'                => 'Abandoned Carts',
        'notification-queued'  => 'Recovery notification dispatched.',
        'max-attempts-reached' => 'Maximum notification attempts already reached.',
        'marked-recovered'     => 'Cart marked as recovered.',
        'cart-expired'         => 'Cart has been expired.',

        'datagrid' => [
            'id'                 => 'ID',
            'customer'           => 'Customer',
            'email'              => 'Email',
            'channel'            => 'Channel',
            'items'              => 'Items',
            'total'              => 'Total',
            'status'             => 'Status',
            'status-active'      => 'Active',
            'status-recovering'  => 'Recovering',
            'status-recovered'   => 'Recovered',
            'status-expired'     => 'Expired',
            'notifications-sent' => 'Notifications Sent',
            'last-activity'      => 'Last Activity',
            'created-at'         => 'Created At',
            'send-now'           => 'Send Now',
            'mark-recovered'     => 'Mark Recovered',
            'expire'             => 'Expire Cart',
        ],
    ],

    'recovery' => [
        'invalid-link'  => 'This recovery link is invalid or has expired.',
        'cart-expired'  => 'Your cart has expired. Please start a new shopping session.',
        'cart-restored' => 'Your cart has been restored! Continue where you left off.',
        'unsubscribed'  => 'You have been unsubscribed from cart reminder notifications.',
    ],

    'emails' => [
        'recovery' => [
            'title'      => 'Your Cart is Waiting',
            'subject-1'  => 'You left something behind 🛒',
            'subject-2'  => 'Your cart is still waiting — items may sell out!',
            'subject-3'  => 'Final reminder: Complete your purchase',
            'there'      => 'there',
            'greeting-1' => 'Hi :name, you left something in your cart!',
            'greeting-2' => 'Hey :name, your cart is still waiting for you.',
            'greeting-3' => 'Last chance, :name — your cart expires soon.',
            'body'        => 'You started a great order but didn\'t finish. Here\'s what you left behind:',
            'qty'         => 'Qty',
            'subtotal'    => 'Subtotal',
            'tax'         => 'Tax',
            'total'       => 'Total',
            'cta'         => 'Complete My Order',
            'privacy-note' => 'You are receiving this because you started a purchase with us. To stop these reminders, ',
            'unsubscribe'  => 'click here to unsubscribe',
            'footer'       => 'All rights reserved.',
        ],
    ],
];
