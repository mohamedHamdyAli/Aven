<x-admin::layouts>
    <x-slot:title>Distribution #{{ $distribution->id }}</x-slot>

    <div class="flex flex-col gap-6 max-w-3xl">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.cost_management.distributions.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">← Back</a>
                <p class="text-xl font-bold text-gray-800 dark:text-white">Distribution #{{ $distribution->id }}</p>
            </div>
            <form method="POST" action="{{ route('admin.cost_management.distributions.destroy', $distribution->id) }}" onsubmit="return confirm('Delete this distribution?')">
                @csrf @method('DELETE')
                <button type="submit" class="rounded-lg border border-red-300 bg-red-50 px-4 py-2 text-sm text-red-600 hover:bg-red-100">Delete</button>
            </form>
        </div>

        {{-- Flash --}}
        @if(session('success'))
            <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700 border border-green-200">{{ session('success') }}</div>
        @endif

        {{-- Summary Card --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-900">
            <div class="grid grid-cols-2 gap-6 sm:grid-cols-4">
                <div>
                    <p class="text-xs text-gray-400 uppercase mb-1">Period</p>
                    <p class="font-semibold text-gray-800 dark:text-white text-sm">
                        {{ $distribution->period_from->format('M j, Y') }}<br>
                        <span class="text-gray-400">to</span> {{ $distribution->period_to->format('M j, Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase mb-1">Net Profit</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ core()->currency($distribution->net_profit) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase mb-1">Total Distributed</p>
                    <p class="text-2xl font-bold text-green-600">{{ core()->currency($distribution->total_distributed) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase mb-1">Created</p>
                    <p class="font-semibold text-gray-800 dark:text-white text-sm">{{ $distribution->created_at->format('M j, Y') }}</p>
                </div>
            </div>
            @if($distribution->notes)
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <p class="text-xs text-gray-400 uppercase mb-1">Notes</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $distribution->notes }}</p>
                </div>
            @endif
        </div>

        {{-- Items Table --}}
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <p class="font-semibold text-gray-800 dark:text-white">Per-Shareholder Breakdown</p>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left">Shareholder</th>
                        <th class="px-5 py-3 text-left">Contact</th>
                        <th class="px-5 py-3 text-right">Share %</th>
                        <th class="px-5 py-3 text-right">Amount</th>
                        <th class="px-5 py-3 text-right">% of Net</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($distribution->items as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-blue-700 text-sm font-bold dark:bg-blue-900 dark:text-blue-300">
                                        {{ strtoupper(substr($item->shareholder?->name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-gray-800 dark:text-white">{{ $item->shareholder?->name ?? 'Deleted' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-gray-500 text-xs">
                                {{ $item->shareholder?->email }}<br>
                                {{ $item->shareholder?->phone }}
                            </td>
                            <td class="px-5 py-3 text-right">
                                <span class="rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ number_format($item->percentage, 2) }}%
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right font-bold text-green-600 text-base">
                                {{ core()->currency($item->amount) }}
                            </td>
                            <td class="px-5 py-3 text-right text-gray-400 text-xs">
                                @if($distribution->net_profit > 0)
                                    {{ number_format(($item->amount / $distribution->net_profit) * 100, 1) }}%
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 dark:bg-gray-800 font-semibold">
                    <tr>
                        <td colspan="3" class="px-5 py-3 text-right text-gray-600 dark:text-gray-300">Total</td>
                        <td class="px-5 py-3 text-right text-green-600">{{ core()->currency($distribution->total_distributed) }}</td>
                        <td class="px-5 py-3 text-right text-gray-400 text-xs">
                            @if($distribution->net_profit > 0)
                                {{ number_format(($distribution->total_distributed / $distribution->net_profit) * 100, 1) }}%
                            @endif
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</x-admin::layouts>
