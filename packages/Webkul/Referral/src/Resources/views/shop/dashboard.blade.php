<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        Referral Program
    </x-slot>

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="mx-4 flex-auto max-md:mx-6 max-sm:mx-4">
        <div class="mb-8 flex items-center max-md:mb-5">
            <h2 class="text-2xl font-medium max-md:text-xl max-sm:text-base">
                Referral Program
            </h2>
        </div>

        @if (session('info'))
            <div class="mb-4 rounded-lg bg-blue-50 px-4 py-3 text-sm text-blue-700">
                {{ session('info') }}
            </div>
        @endif

        <!-- Referral Link Card -->
        <div class="mb-6 rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
            <h3 class="mb-3 text-lg font-semibold text-gray-800">Your Referral Link</h3>
            <p class="mb-4 text-sm text-zinc-500">Share this link with friends. When they place their first order, you earn a reward.</p>

            <div class="flex items-center gap-2">
                <input
                    id="referral-link-input"
                    type="text"
                    class="flex-1 rounded-lg border border-zinc-300 bg-zinc-50 px-4 py-2.5 text-sm text-gray-700 focus:outline-none"
                    value="{{ $shareUrl }}"
                    readonly
                />
                <button
                    type="button"
                    onclick="navigator.clipboard.writeText('{{ $shareUrl }}').then(() => { this.textContent = 'Copied!'; setTimeout(() => this.textContent = 'Copy', 2000); })"
                    class="primary-button rounded-lg px-5 py-2.5 text-sm"
                >
                    Copy
                </button>
            </div>

            <p class="mt-3 text-xs text-zinc-400">
                Your code: <span class="font-semibold text-gray-700">{{ $code }}</span>
            </p>
        </div>

        <!-- Stats -->
        <div class="mb-6 grid grid-cols-2 gap-4 max-sm:grid-cols-1">
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-zinc-500">Times Referred</p>
                <p class="mt-1 text-3xl font-bold text-gray-800">{{ $referral?->times_used ?? 0 }}</p>
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-zinc-500">Total Earned</p>
                <p class="mt-1 text-3xl font-bold text-gray-800">
                    {{ core()->formatPrice($referral?->total_earned ?? 0) }}
                </p>
            </div>
        </div>

        <!-- Conversions Table -->
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm">
            <div class="border-b border-zinc-200 px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Referrals</h3>
            </div>

            @if ($conversions->isEmpty())
                <div class="px-6 py-10 text-center text-zinc-500">
                    No referrals yet. Start sharing your link!
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-zinc-100 bg-zinc-50 text-left text-xs uppercase text-zinc-500">
                                <th class="px-6 py-3 font-medium">Email</th>
                                <th class="px-6 py-3 font-medium">Date</th>
                                <th class="px-6 py-3 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @foreach ($conversions as $conversion)
                                <tr class="hover:bg-zinc-50">
                                    <td class="px-6 py-3 text-gray-700">
                                        {{ $conversion->referred_email ?? '—' }}
                                    </td>
                                    <td class="px-6 py-3 text-zinc-500">
                                        {{ \Carbon\Carbon::parse($conversion->created_at)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-3">
                                        @if ($conversion->status === 'rewarded')
                                            <span class="inline-block rounded-full bg-green-100 px-3 py-0.5 text-xs font-medium text-green-700">
                                                Rewarded
                                            </span>
                                        @else
                                            <span class="inline-block rounded-full bg-yellow-100 px-3 py-0.5 text-xs font-medium text-yellow-700">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-shop::layouts.account>
