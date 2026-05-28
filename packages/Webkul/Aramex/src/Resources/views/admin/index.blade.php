@extends('admin::layouts.master')

@section('title')
    Aramex Shipments
@endsection

@section('content')
    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">Aramex Shipments</p>
    </div>

    <div class="mt-6 box-shadow rounded-xl bg-white dark:bg-gray-900">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="bg-gray-50 dark:bg-gray-700 text-xs text-gray-700 dark:text-gray-300 uppercase">
                <tr>
                    <th class="px-6 py-3">Order</th>
                    <th class="px-6 py-3">Aramex ID</th>
                    <th class="px-6 py-3">Waybill #</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Created</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($deliveries as $d)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.sales.orders.view', $d->order_id) }}" class="text-navyBlue hover:underline font-medium">
                                #{{ $d->order_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs">{{ $d->aramex_id ?? '—' }}</td>
                        <td class="px-6 py-4">
                            @if ($d->waybill_number)
                                <a
                                    href="https://www.aramex.com/track/results?mode=0&ShipmentNumber={{ $d->waybill_number }}"
                                    target="_blank"
                                    class="font-mono font-bold text-navyBlue hover:underline"
                                >{{ $d->waybill_number }}</a>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColor = match($d->status) {
                                    'created' => 'bg-green-100 text-green-700',
                                    'failed'  => 'bg-red-100 text-red-600',
                                    default   => 'bg-gray-100 text-gray-500',
                                };
                            @endphp
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $statusColor }}">
                                {{ ucfirst($d->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($d->created_at)->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            @if (! $d->waybill_number)
                                <button
                                    onclick="createShipment({{ $d->order_id }}, this)"
                                    class="text-sm text-navyBlue hover:underline"
                                >Create Shipment</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-400">No Aramex shipments yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $deliveries->links() }}
        </div>
    </div>

    @pushOnce('scripts')
        <script>
        function createShipment(orderId, btn) {
            btn.disabled = true;
            btn.textContent = 'Creating...';
            fetch(`{{ url('admin/aramex/orders') }}/${orderId}/create-shipment`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    btn.textContent = '✓ ' + (d.waybill_number || 'Created');
                    btn.className = 'text-sm text-green-600';
                } else {
                    btn.textContent = 'Failed';
                    btn.className = 'text-sm text-red-500';
                    btn.disabled = false;
                }
            })
            .catch(() => { btn.textContent = 'Error'; btn.disabled = false; });
        }
        </script>
    @endPushOnce
@endsection
