<x-shop::layouts>
    <x-slot:title>Order #{{ $order->increment_id }} — Tracking</x-slot>

    <div class="container mx-auto px-4 py-10 max-w-2xl">
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('shop.order.track.index') }}" class="text-sm text-indigo-600 hover:underline">← Track Another Order</a>
        </div>

        <!-- Status Header -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Order Number</p>
                    <p class="text-xl font-bold text-gray-900">#{{ $order->increment_id }}</p>
                    <p class="mt-1 text-sm text-gray-500">Placed {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, g:i A') }}</p>
                </div>

                @php
                    $statusConfig = [
                        'pending'         => ['label' => 'Pending',          'color' => 'bg-yellow-100 text-yellow-800'],
                        'processing'      => ['label' => 'Processing',        'color' => 'bg-blue-100 text-blue-800'],
                        'completed'       => ['label' => 'Delivered',         'color' => 'bg-green-100 text-green-800'],
                        'closed'          => ['label' => 'Closed',            'color' => 'bg-gray-100 text-gray-700'],
                        'canceled'        => ['label' => 'Cancelled',         'color' => 'bg-red-100 text-red-700'],
                        'pending_payment' => ['label' => 'Pending Payment',   'color' => 'bg-orange-100 text-orange-700'],
                    ];
                    $sc = $statusConfig[$order->status] ?? ['label' => ucfirst($order->status), 'color' => 'bg-gray-100 text-gray-700'];
                @endphp

                <span class="rounded-full px-4 py-1.5 text-sm font-semibold {{ $sc['color'] }}">{{ $sc['label'] }}</span>
            </div>

            <!-- Progress Bar -->
            @php
                $steps = ['pending', 'processing', 'completed'];
                $currentStep = array_search($order->status, $steps);
                if ($currentStep === false) $currentStep = -1;
            @endphp
            <div class="mt-6 flex items-center gap-0">
                @foreach (['Order Placed', 'Processing', 'Delivered'] as $i => $step)
                    <div class="flex flex-1 flex-col items-center">
                        <div class="h-8 w-8 rounded-full flex items-center justify-center text-sm font-bold
                            {{ $i <= $currentStep ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                            {{ $i <= $currentStep ? '✓' : ($i + 1) }}
                        </div>
                        <p class="mt-1 text-center text-xs text-gray-500">{{ $step }}</p>
                    </div>
                    @if (! $loop->last)
                        <div class="h-1 flex-1 {{ $i < $currentStep ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Shipment Info -->
        @if ($shipments->isNotEmpty())
            <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-sm font-semibold text-gray-700">Shipment Details</h2>
                @foreach ($shipments as $shipment)
                    <div class="rounded-lg bg-gray-50 p-4">
                        <div class="flex flex-wrap justify-between gap-2 text-sm">
                            <span class="text-gray-600">Carrier: <span class="font-medium text-gray-900">{{ $shipment->carrier_title ?? $shipment->carrier_code ?? 'N/A' }}</span></span>
                            @if ($shipment->track_number)
                                <span class="text-gray-600">Tracking #: <span class="font-mono font-semibold text-indigo-700">{{ $shipment->track_number }}</span></span>
                            @endif
                            <span class="text-gray-500 text-xs">{{ \Carbon\Carbon::parse($shipment->created_at)->format('d M Y') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Order Items -->
        <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-sm font-semibold text-gray-700">Items Ordered</h2>
            <div class="divide-y divide-gray-100">
                @foreach ($items as $item)
                    <div class="flex items-center justify-between py-3">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $item->name }}</p>
                            <p class="text-xs text-gray-400">SKU: {{ $item->sku }} · Qty: {{ (int) $item->qty_ordered }}</p>
                        </div>
                        <p class="text-sm font-semibold text-gray-800">{{ core()->formatPrice($item->total) }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 flex justify-between border-t border-gray-100 pt-4">
                <span class="font-semibold text-gray-700">Total</span>
                <span class="font-bold text-gray-900">{{ core()->formatPrice($order->grand_total) }}</span>
            </div>
        </div>

        <p class="mt-6 text-center text-xs text-gray-400">
            Need help? Contact our support team.
        </p>
    </div>
</x-shop::layouts>
