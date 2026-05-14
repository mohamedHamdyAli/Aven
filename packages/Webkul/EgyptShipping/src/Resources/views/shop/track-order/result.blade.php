@php
    $statusMap = [
        'pending'         => ['label' => 'طلب جديد',          'en' => 'New Order',        'color' => 'yellow',  'step' => 1],
        'pending_payment' => ['label' => 'في انتظار الدفع',   'en' => 'Awaiting Payment', 'color' => 'orange',  'step' => 1],
        'processing'      => ['label' => 'جاري التجهيز',      'en' => 'Processing',       'color' => 'blue',    'step' => 2],
        'completed'       => ['label' => 'تم التوصيل',        'en' => 'Delivered',        'color' => 'green',   'step' => 4],
        'canceled'        => ['label' => 'ملغي',              'en' => 'Canceled',         'color' => 'red',     'step' => 0],
        'closed'          => ['label' => 'مغلق',              'en' => 'Closed',           'color' => 'gray',    'step' => 4],
        'fraud'           => ['label' => 'قيد المراجعة',      'en' => 'Under Review',     'color' => 'gray',    'step' => 0],
    ];

    $current = $statusMap[$order->status] ?? ['label' => $order->status, 'en' => $order->status, 'color' => 'gray', 'step' => 0];

    $colorClasses = [
        'yellow' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
        'orange' => 'bg-orange-100 text-orange-800 border-orange-300',
        'blue'   => 'bg-blue-100 text-blue-800 border-blue-300',
        'green'  => 'bg-green-100 text-green-800 border-green-300',
        'red'    => 'bg-red-100 text-red-800 border-red-300',
        'gray'   => 'bg-gray-100 text-gray-800 border-gray-300',
    ];

    $timelineSteps = [
        1 => ['ar' => 'طلب جديد',       'en' => 'Order Placed',  'icon' => '📦'],
        2 => ['ar' => 'جاري التجهيز',   'en' => 'Processing',    'icon' => '⚙️'],
        3 => ['ar' => 'تم الشحن',       'en' => 'Shipped',       'icon' => '🚚'],
        4 => ['ar' => 'تم التوصيل',     'en' => 'Delivered',     'icon' => '✅'],
    ];

    $activeStep = $current['step'];
    $shipment   = $order->shipments->first();
@endphp

<x-shop::layouts>
    <x-slot:title>
        @lang('egypt-shipping::app.track-order.result-title', ['id' => $order->increment_id])
    </x-slot>

    <div class="container mx-auto mt-8 mb-16 px-4 max-w-3xl">

        {{-- Back link --}}
        <a
            href="{{ route('egypt-shipping.track-order.index') }}"
            class="mb-6 inline-flex items-center gap-1 text-sm text-blue-600 hover:underline"
        >
            ← @lang('egypt-shipping::app.track-order.back')
        </a>

        {{-- Header: Order # + Status badge --}}
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                    @lang('egypt-shipping::app.track-order.order-number', ['id' => $order->increment_id])
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            <span class="rounded-full border px-4 py-1 text-sm font-semibold {{ $colorClasses[$current['color']] }}">
                {{ $current['label'] }} &mdash; {{ $current['en'] }}
            </span>
        </div>

        {{-- Status Timeline --}}
        @if ($activeStep > 0)
            <div class="mb-6 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    @foreach ($timelineSteps as $step => $info)
                        <div class="flex flex-1 flex-col items-center">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full text-lg
                                {{ $step <= $activeStep ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-400 dark:bg-gray-700' }}">
                                {{ $info['icon'] }}
                            </div>
                            <p class="mt-2 text-center text-xs font-medium
                                {{ $step <= $activeStep ? 'text-blue-600' : 'text-gray-400' }}">
                                {{ $info['ar'] }}
                            </p>
                            <p class="text-center text-xs text-gray-400">{{ $info['en'] }}</p>
                        </div>

                        @if (!$loop->last)
                            <div class="h-0.5 flex-1 mx-1 {{ $step < $activeStep ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700' }}"></div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Order Items --}}
        <div class="mb-6 rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900">
            <div class="border-b border-gray-100 px-6 py-4 dark:border-gray-700">
                <h2 class="font-semibold text-gray-800 dark:text-white">@lang('egypt-shipping::app.track-order.items')</h2>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($order->items as $item)
                    <div class="flex items-center gap-4 px-6 py-4">
                        @if ($item->product?->base_image_url)
                            <img
                                src="{{ $item->product->base_image_url }}"
                                alt="{{ $item->name }}"
                                class="h-14 w-14 rounded-lg object-cover"
                            >
                        @else
                            <div class="flex h-14 w-14 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-400 text-xl">📦</div>
                        @endif
                        <div class="flex-1">
                            <p class="font-medium text-gray-800 dark:text-white">{{ $item->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item->sku }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-800 dark:text-white">{{ core()->formatPrice($item->total, $order->order_currency_code) }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">× {{ (int) $item->qty_ordered }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

            {{-- Shipping Info --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-900">
                <h2 class="mb-4 font-semibold text-gray-800 dark:text-white">@lang('egypt-shipping::app.track-order.shipping-info')</h2>

                @if ($order->shipping_address)
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        {{ $order->shipping_address->first_name }} {{ $order->shipping_address->last_name }}<br>
                        {{ $order->shipping_address->address1 }}<br>
                        @if ($order->shipping_address->address2)
                            {{ $order->shipping_address->address2 }}<br>
                        @endif
                        {{ $order->shipping_address->city }}, {{ $order->shipping_address->state }}<br>
                        {{ $order->shipping_address->country }}
                    </p>
                @endif

                @if ($order->shipping_title)
                    <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                        <span class="font-medium">@lang('egypt-shipping::app.track-order.carrier'):</span>
                        {{ $order->shipping_title }}
                    </p>
                @endif

                @if ($shipment)
                    @if ($shipment->track_number)
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            <span class="font-medium">@lang('egypt-shipping::app.track-order.tracking-number'):</span>
                            {{ $shipment->track_number }}
                        </p>
                    @endif
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        <span class="font-medium">@lang('egypt-shipping::app.track-order.shipped-at'):</span>
                        {{ $shipment->created_at->format('d M Y') }}
                    </p>
                @endif
            </div>

            {{-- Order Summary --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-900">
                <h2 class="mb-4 font-semibold text-gray-800 dark:text-white">@lang('egypt-shipping::app.track-order.summary')</h2>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600 dark:text-gray-300">
                        <span>@lang('egypt-shipping::app.track-order.subtotal')</span>
                        <span>{{ core()->formatPrice($order->sub_total, $order->order_currency_code) }}</span>
                    </div>

                    @if ($order->shipping_amount > 0)
                        <div class="flex justify-between text-gray-600 dark:text-gray-300">
                            <span>@lang('egypt-shipping::app.track-order.shipping')</span>
                            <span>{{ core()->formatPrice($order->shipping_amount, $order->order_currency_code) }}</span>
                        </div>
                    @endif

                    @if ($order->discount_amount > 0)
                        <div class="flex justify-between text-green-600">
                            <span>@lang('egypt-shipping::app.track-order.discount')</span>
                            <span>- {{ core()->formatPrice($order->discount_amount, $order->order_currency_code) }}</span>
                        </div>
                    @endif

                    @if ($order->tax_amount > 0)
                        <div class="flex justify-between text-gray-600 dark:text-gray-300">
                            <span>@lang('egypt-shipping::app.track-order.tax')</span>
                            <span>{{ core()->formatPrice($order->tax_amount, $order->order_currency_code) }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between border-t border-gray-200 pt-2 font-bold text-gray-800 dark:border-gray-600 dark:text-white">
                        <span>@lang('egypt-shipping::app.track-order.grand-total')</span>
                        <span>{{ core()->formatPrice($order->grand_total, $order->order_currency_code) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-shop::layouts>
