<?php

namespace Webkul\Admin\Http\Controllers\Settings;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;

class SocialController extends Controller
{
    public function index(): View
    {
        $pushSubscribers = 0;
        try {
            $pushSubscribers = DB::table('push_subscriptions')->count();
        } catch (\Throwable) {}

        $socialCommerceChannels = 0;
        try {
            $socialCommerceChannels = DB::table('social_commerce_channels')->count();
        } catch (\Throwable) {}

        $groups = [
            [
                'label'        => 'Tracking & Analytics',
                'integrations' => [
                    [
                        'key'         => 'facebook_pixel',
                        'title'       => 'Facebook Pixel',
                        'description' => 'Track conversions & retarget visitors via Facebook',
                        'logo'        => 'fab fa-facebook',
                        'color'       => '#1877F2',
                        'active'      => (bool) core()->getConfigData('general.content.facebook_pixel.enabled'),
                        'config_url'  => route('admin.configuration.index', ['slug' => 'general', 'slug2' => 'content']),
                    ],
                    [
                        'key'         => 'facebook_conversions',
                        'title'       => 'Facebook Conversions API',
                        'description' => 'Server-side Purchase events sent to Facebook',
                        'logo'        => 'fab fa-facebook',
                        'color'       => '#1877F2',
                        'active'      => (bool) core()->getConfigData('general.content.facebook_pixel.conversions_api_token'),
                        'config_url'  => route('admin.configuration.index', ['slug' => 'general', 'slug2' => 'content']),
                    ],
                    [
                        'key'         => 'tiktok_pixel',
                        'title'       => 'TikTok Pixel',
                        'description' => 'Measure ad performance on TikTok',
                        'logo'        => 'fab fa-tiktok',
                        'color'       => '#010101',
                        'active'      => (bool) core()->getConfigData('general.content.tiktok_pixel.enabled'),
                        'config_url'  => route('admin.configuration.index', ['slug' => 'general', 'slug2' => 'content']),
                    ],
                    [
                        'key'         => 'google_analytics',
                        'title'       => 'Google Analytics (GA4)',
                        'description' => 'Track visitor behaviour with Google Analytics',
                        'logo'        => 'fab fa-google',
                        'color'       => '#E37400',
                        'active'      => (bool) core()->getConfigData('general.content.google_analytics.enabled'),
                        'config_url'  => route('admin.configuration.index', ['slug' => 'general', 'slug2' => 'content']),
                    ],
                    [
                        'key'         => 'google_tag_manager',
                        'title'       => 'Google Tag Manager',
                        'description' => 'Manage all tags from one place',
                        'logo'        => 'fab fa-google',
                        'color'       => '#4285F4',
                        'active'      => (bool) core()->getConfigData('general.content.google_tag_manager.enabled'),
                        'config_url'  => route('admin.configuration.index', ['slug' => 'general', 'slug2' => 'content']),
                    ],
                ],
            ],
            [
                'label'        => 'Social Commerce',
                'integrations' => [
                    [
                        'key'         => 'social_commerce',
                        'title'       => 'Social Commerce Channels',
                        'description' => 'Sell on Facebook Shop, Instagram, TikTok Shop & more',
                        'logo'        => 'fas fa-store',
                        'color'       => '#E1306C',
                        'active'      => $socialCommerceChannels > 0,
                        'config_url'  => route('admin.social-commerce.channels.index'),
                        'badge'       => $socialCommerceChannels > 0 ? $socialCommerceChannels . ' channel' . ($socialCommerceChannels > 1 ? 's' : '') : null,
                        'is_page'     => true,
                    ],
                ],
            ],
            [
                'label'        => 'Social Login',
                'integrations' => [
                    [
                        'key'         => 'facebook_login',
                        'title'       => 'Facebook Login',
                        'description' => 'Let customers sign in with Facebook',
                        'logo'        => 'fab fa-facebook',
                        'color'       => '#1877F2',
                        'active'      => (bool) core()->getConfigData('customer.settings.social_login.facebook_login'),
                        'config_url'  => route('admin.configuration.index', ['slug' => 'customer', 'slug2' => 'settings']),
                    ],
                    [
                        'key'         => 'google_login',
                        'title'       => 'Google Login',
                        'description' => 'Let customers sign in with Google',
                        'logo'        => 'fab fa-google',
                        'color'       => '#EA4335',
                        'active'      => (bool) core()->getConfigData('customer.settings.social_login.google_login'),
                        'config_url'  => route('admin.configuration.index', ['slug' => 'customer', 'slug2' => 'settings']),
                    ],
                ],
            ],
            [
                'label'        => 'Social Sharing',
                'integrations' => [
                    [
                        'key'         => 'social_share',
                        'title'       => 'Product Share Buttons',
                        'description' => 'Share buttons on product pages (Facebook, Twitter, etc.)',
                        'logo'        => 'fas fa-share-alt',
                        'color'       => '#6366F1',
                        'active'      => (bool) core()->getConfigData('catalog.products.social_share.enabled'),
                        'config_url'  => route('admin.configuration.index', ['slug' => 'catalog', 'slug2' => 'products']),
                    ],
                ],
            ],
            [
                'label'        => 'Live Chat',
                'integrations' => [
                    [
                        'key'         => 'tawk',
                        'title'       => 'Tawk.to',
                        'description' => 'Free live chat widget for your store',
                        'logo'        => 'fas fa-comments',
                        'color'       => '#03C5CB',
                        'active'      => (bool) core()->getConfigData('general.content.tawk_chat.enabled'),
                        'config_url'  => route('admin.configuration.index', ['slug' => 'general', 'slug2' => 'content']),
                    ],
                ],
            ],
            [
                'label'        => 'Messaging & Notifications',
                'integrations' => [
                    [
                        'key'         => 'whatsapp_chat',
                        'title'       => 'WhatsApp Chat Button',
                        'description' => 'Floating WhatsApp support button on all shop pages',
                        'logo'        => 'fab fa-whatsapp',
                        'color'       => '#25D366',
                        'active'      => (bool) core()->getConfigData('general.content.whatsapp_chat.enabled'),
                        'config_url'  => route('admin.configuration.index', ['slug' => 'general', 'slug2' => 'content']),
                    ],
                    [
                        'key'         => 'whatsapp_abandoned',
                        'title'       => 'WhatsApp Abandoned Cart',
                        'description' => 'Recover carts by messaging customers on WhatsApp',
                        'logo'        => 'fab fa-whatsapp',
                        'color'       => '#25D366',
                        'active'      => (bool) core()->getConfigData('sales.abandoned_cart.channels.whatsapp_enabled'),
                        'config_url'  => route('admin.configuration.index', ['slug' => 'sales', 'slug2' => 'abandoned_cart']),
                    ],
                    [
                        'key'         => 'email_abandoned',
                        'title'       => 'Email Abandoned Cart',
                        'description' => 'Automatically email customers who left items in their cart',
                        'logo'        => 'fas fa-envelope',
                        'color'       => '#F59E0B',
                        'active'      => (bool) core()->getConfigData('sales.abandoned_cart.channels.email_enabled'),
                        'config_url'  => route('admin.configuration.index', ['slug' => 'sales', 'slug2' => 'abandoned_cart']),
                    ],
                    [
                        'key'         => 'whatsapp_orders',
                        'title'       => 'WhatsApp Order Notifications',
                        'description' => 'Notify customers via WhatsApp on order placed, shipped, delivered',
                        'logo'        => 'fab fa-whatsapp',
                        'color'       => '#25D366',
                        'active'      => (bool) core()->getConfigData('sales.order_notification.general.enabled'),
                        'config_url'  => route('admin.configuration.index', ['slug' => 'sales', 'slug2' => 'order_notification']),
                    ],
                    [
                        'key'         => 'sms',
                        'title'       => 'SMS Notifications',
                        'description' => 'Send SMS to customers on order events via Vonage or Twilio',
                        'logo'        => 'fas fa-sms',
                        'color'       => '#7C3AED',
                        'active'      => (bool) core()->getConfigData('sales.sms_notification.enabled'),
                        'config_url'  => route('admin.configuration.index', ['slug' => 'sales', 'slug2' => 'sms_notification']),
                    ],
                    [
                        'key'         => 'push_notifications',
                        'title'       => 'Push Notifications',
                        'description' => 'Browser push campaigns to subscribed customers',
                        'logo'        => 'fas fa-bell',
                        'color'       => '#8B5CF6',
                        'active'      => $pushSubscribers > 0,
                        'config_url'  => route('admin.push.index'),
                        'badge'       => $pushSubscribers > 0 ? $pushSubscribers . ' subscriber' . ($pushSubscribers > 1 ? 's' : '') : null,
                        'is_page'     => true,
                    ],
                ],
            ],
        ];

        return view('admin::settings.social.index', compact('groups'));
    }
}
