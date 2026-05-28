<?php

namespace Webkul\Admin\Http\Controllers\Settings;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;

class ShippingController extends Controller
{
    public function index(): View
    {
        $bostaCount   = $this->safeCount('bosta_deliveries');
        $aramexCount  = $this->safeCount('aramex_deliveries');
        $egyptRates   = $this->safeCount('egypt_shipping_rates');

        $groups = [
            [
                'label' => 'Carriers',
                'items' => [
                    [
                        'title'       => 'Free Shipping',
                        'description' => 'Offer free shipping based on order conditions',
                        'icon'        => 'icon-bag',
                        'color'       => '#10B981',
                        'active'      => (bool) core()->getConfigData('sales.carriers.free.active'),
                        'config_url'  => route('admin.configuration.index', ['slug' => 'sales', 'slug2' => 'carriers']),
                        'link_label'  => 'Configure',
                    ],
                    [
                        'title'       => 'Flat Rate',
                        'description' => 'Fixed shipping rate applied to all orders',
                        'icon'        => 'icon-dollar',
                        'color'       => '#3B82F6',
                        'active'      => (bool) core()->getConfigData('sales.carriers.flatrate.active'),
                        'config_url'  => route('admin.configuration.index', ['slug' => 'sales', 'slug2' => 'carriers']),
                        'link_label'  => 'Configure',
                    ],
                    [
                        'title'       => 'Bosta',
                        'description' => 'Egyptian last-mile delivery with auto-shipment creation',
                        'icon'        => 'icon-ship',
                        'color'       => '#EF4444',
                        'active'      => (bool) core()->getConfigData('sales.carriers.bosta.active'),
                        'badge'       => $bostaCount > 0 ? $bostaCount . ' shipment' . ($bostaCount != 1 ? 's' : '') : null,
                        'config_url'  => route('admin.bosta.index'),
                        'link_label'  => 'Manage',
                    ],
                    [
                        'title'       => 'Aramex',
                        'description' => 'Aramex courier with API integration & tracking',
                        'icon'        => 'icon-ship',
                        'color'       => '#F97316',
                        'active'      => (bool) core()->getConfigData('sales.carriers.aramex.active'),
                        'badge'       => $aramexCount > 0 ? $aramexCount . ' shipment' . ($aramexCount != 1 ? 's' : '') : null,
                        'config_url'  => route('admin.aramex.index'),
                        'link_label'  => 'Manage',
                    ],
                    [
                        'title'       => 'Egypt Shipping — Governorate Rates',
                        'description' => 'Per-governorate shipping rates for Egyptian customers',
                        'icon'        => 'icon-map',
                        'color'       => '#6366F1',
                        'active'      => $egyptRates > 0,
                        'badge'       => $egyptRates > 0 ? $egyptRates . ' governorate' . ($egyptRates != 1 ? 's' : '') : null,
                        'config_url'  => route('admin.egypt-shipping.index'),
                        'link_label'  => 'Manage Rates',
                    ],
                ],
            ],
            [
                'label' => 'Store Settings',
                'items' => [
                    [
                        'title'       => 'Shipping Origin',
                        'description' => 'Default warehouse address used for shipping calculations',
                        'icon'        => 'icon-location',
                        'color'       => '#64748B',
                        'active'      => (bool) core()->getConfigData('sales.shipping.origin.country'),
                        'config_url'  => route('admin.configuration.index', ['slug' => 'sales', 'slug2' => 'shipping']),
                        'link_label'  => 'Configure',
                    ],
                    [
                        'title'       => 'Free Shipping Bar',
                        'description' => 'Show a progress bar encouraging customers to unlock free shipping',
                        'icon'        => 'icon-offer',
                        'color'       => '#10B981',
                        'active'      => (bool) core()->getConfigData('general.design.free_shipping_bar.enabled'),
                        'config_url'  => route('admin.configuration.index', ['slug' => 'general', 'slug2' => 'design']),
                        'link_label'  => 'Configure',
                    ],
                    [
                        'title'       => 'RMA — Returns & Exchanges',
                        'description' => 'Manage return requests, reasons, rules and statuses',
                        'icon'        => 'icon-undo',
                        'color'       => '#8B5CF6',
                        'active'      => (bool) core()->getConfigData('sales.rma.setting.rma_status'),
                        'config_url'  => route('admin.sales.rma.requests.index'),
                        'link_label'  => 'Manage',
                    ],
                ],
            ],
        ];

        return view('admin::settings.shipping.index', compact('groups'));
    }

    private function safeCount(string $table): int
    {
        try {
            return DB::table($table)->count();
        } catch (\Throwable) {
            return 0;
        }
    }
}
