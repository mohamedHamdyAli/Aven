<?php

namespace Webkul\Admin\Http\Controllers\Settings;

use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;

class PaymentMethodsController extends Controller
{
    private array $methods = [
        [
            'key'         => 'cashondelivery',
            'title'       => 'Cash on Delivery',
            'description' => 'Customer pays upon delivery',
            'icon'        => 'icon-money',
        ],
        [
            'key'         => 'moneytransfer',
            'title'       => 'Bank Transfer',
            'description' => 'Customer transfers to your bank account',
            'icon'        => 'icon-transfer',
        ],
        [
            'key'         => 'paymob',
            'title'       => 'Paymob',
            'description' => 'Credit/Debit cards, Wallet via Paymob',
            'icon'        => 'icon-credit-card',
        ],
        [
            'key'         => 'fawry',
            'title'       => 'Fawry',
            'description' => 'Pay at any Fawry outlet',
            'icon'        => 'icon-credit-card',
        ],
        [
            'key'         => 'valu',
            'title'       => 'valU',
            'description' => 'Buy now, pay later in installments',
            'icon'        => 'icon-credit-card',
        ],
        [
            'key'         => 'stripe',
            'title'       => 'Stripe',
            'description' => 'Credit/Debit cards via Stripe',
            'icon'        => 'icon-credit-card',
        ],
        [
            'key'         => 'razorpay',
            'title'       => 'Razorpay',
            'description' => 'Cards, UPI, wallets via Razorpay',
            'icon'        => 'icon-credit-card',
        ],
        [
            'key'         => 'payu',
            'title'       => 'PayU',
            'description' => 'Cards and local payments via PayU',
            'icon'        => 'icon-credit-card',
        ],
        [
            'key'         => 'paypal_standard',
            'title'       => 'PayPal Standard',
            'description' => 'PayPal redirect checkout',
            'icon'        => 'icon-credit-card',
        ],
        [
            'key'         => 'paypal_smart_button',
            'title'       => 'PayPal Smart Button',
            'description' => 'Inline PayPal / card button',
            'icon'        => 'icon-credit-card',
        ],
    ];

    public function index(): View
    {
        $methods = collect($this->methods)->map(function ($method) {
            $base = 'sales.payment_methods.' . $method['key'];

            $method['active'] = (bool) core()->getConfigData($base . '.active');
            $method['title']  = core()->getConfigData($base . '.title') ?: $method['title'];

            return $method;
        });

        $configUrl = route('admin.configuration.index', [
            'slug'  => 'sales',
            'slug2' => 'payment_methods',
        ]);

        return view('admin::settings.payment-methods.index', compact('methods', 'configUrl'));
    }
}
