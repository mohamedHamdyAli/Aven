<x-admin::layouts>
    <x-slot:title>Profit Distributions</x-slot>

    <div class="flex flex-col gap-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xl font-bold text-gray-800 dark:text-white">Profit Distributions</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">History of distributed profits to shareholders</p>
            </div>
            <a href="{{ route('admin.cost_management.distributions.create') }}" class="primary-button">
                + New Distribution
            </a>
        </div>

        {{-- Flash --}}
        @if(session('success'))
            <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700 border border-green-200">{{ session('success') }}</div>
        @endif

        {{-- Table --}}
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900 overflow-hidden">
            @if($distributions->isEmpty())
                <div class="p-12 text-center text-gray-400">
                    No distributions yet. Click "+ New Distribution" to create one.
                </div>
            @else
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800 text-xs text-gray-500 dark:text-gray-400 uppercase">
                        <tr>
                            <th class="px-5 py-3 text-left">#</th>
                            <th class="px-5 py-3 text-left">Period</th>
                            <th class="px-5 py-3 text-right">Net Profit</th>
                            <th class="px-5 py-3 text-right">Total Distributed</th>
                            <th class="px-5 py-3 text-left">Shareholders</th>
                            <th class="px-5 py-3 text-left">Notes</th>
                            <th class="px-5 py-3 text-left">Date</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($distributions as $dist)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <td class="px-5 py-3 text-gray-400">{{ $dist->id }}</td>
                                <td class="px-5 py-3 font-medium text-gray-800 dark:text-white">
                                    {{ $dist->period_from->format('M j, Y') }} – {{ $dist->period_to->format('M j, Y') }}
                                </td>
                                <td class="px-5 py-3 text-right font-semibold text-gray-800 dark:text-white">
                                    {{ core()->currency($dist->net_profit) }}
                                </td>
                                <td class="px-5 py-3 text-right font-semibold text-green-600">
                                    {{ core()->currency($dist->total_distributed) }}
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center gap-1">
                                        <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                                            {{ $dist->items->count() }} shareholders
                                        </span>
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-gray-500 max-w-[160px] truncate">{{ $dist->notes }}</td>
                                <td class="px-5 py-3 text-gray-400 text-xs">{{ $dist->created_at->format('M j, Y') }}</td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('admin.cost_management.distributions.show', $dist->id) }}" class="text-blue-600 hover:underline text-xs">View</a>
                                        <form method="POST" action="{{ route('admin.cost_management.distributions.destroy', $dist->id) }}" onsubmit="return confirm('Delete this distribution?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline text-xs">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-700">
                    {{ $distributions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin::layouts>
