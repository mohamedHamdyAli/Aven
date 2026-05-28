<x-admin::layouts>
    <x-slot:title>
        Referral Program
    </x-slot>

    <div class="mt-3 flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            Referral Program
        </p>
    </div>

    <!-- Stats Summary -->
    <div class="mt-4 grid grid-cols-3 gap-4 max-sm:grid-cols-1">
        <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-gray-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Total Referrers</p>
            <p class="mt-1 text-2xl font-bold text-gray-800 dark:text-white">{{ $referrals->total() }}</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-gray-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Total Conversions</p>
            <p class="mt-1 text-2xl font-bold text-gray-800 dark:text-white">{{ $referrals->sum('times_used') }}</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-gray-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Total Rewards Issued</p>
            <p class="mt-1 text-2xl font-bold text-gray-800 dark:text-white">
                {{ core()->formatPrice($referrals->sum('total_earned')) }}
            </p>
        </div>
    </div>

    <!-- Table -->
    <div class="mt-6 overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-gray-900">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-zinc-200 bg-zinc-50 text-left text-xs uppercase text-zinc-500 dark:border-zinc-700 dark:bg-gray-800 dark:text-zinc-400">
                        <th class="px-6 py-3 font-medium">#</th>
                        <th class="px-6 py-3 font-medium">Customer Name</th>
                        <th class="px-6 py-3 font-medium">Email</th>
                        <th class="px-6 py-3 font-medium">Referral Code</th>
                        <th class="px-6 py-3 font-medium text-right">Times Used</th>
                        <th class="px-6 py-3 font-medium text-right">Total Earned</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                    @forelse ($referrals as $referral)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-gray-800">
                            <td class="px-6 py-3 text-zinc-500">{{ $referral->id }}</td>
                            <td class="px-6 py-3 font-medium text-gray-800 dark:text-white">
                                {{ $referral->customer_name }}
                            </td>
                            <td class="px-6 py-3 text-zinc-500">{{ $referral->email }}</td>
                            <td class="px-6 py-3">
                                <span class="rounded bg-zinc-100 px-2 py-0.5 font-mono text-xs text-gray-700 dark:bg-zinc-800 dark:text-zinc-300">
                                    {{ $referral->referral_code }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-right text-gray-700 dark:text-zinc-300">
                                {{ $referral->times_used }}
                            </td>
                            <td class="px-6 py-3 text-right font-medium text-gray-800 dark:text-white">
                                {{ core()->formatPrice($referral->total_earned) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-zinc-500">
                                No referral data yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($referrals->hasPages())
            <div class="border-t border-zinc-200 px-6 py-4 dark:border-zinc-700">
                {{ $referrals->links() }}
            </div>
        @endif
    </div>

</x-admin::layouts>
