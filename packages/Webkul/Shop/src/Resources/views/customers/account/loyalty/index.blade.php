<x-shop::layouts.account>
    <x-slot:title>
        @lang('shop::app.customers.account.loyalty.title')
    </x-slot>

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="mx-4 flex-auto max-md:mx-6 max-sm:mx-4">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a class="grid md:hidden" href="{{ route('shop.customers.account.index') }}">
                    <span class="icon-arrow-left text-2xl rtl:icon-arrow-right"></span>
                </a>
                <h2 class="text-2xl font-medium ltr:ml-2.5 md:ltr:ml-0 rtl:mr-2.5 md:rtl:mr-0 max-md:text-xl max-sm:text-base">
                    @lang('shop::app.customers.account.loyalty.title')
                </h2>
            </div>
        </div>

        <!-- Balance Card -->
        <div class="mt-6 grid grid-cols-3 gap-5 max-sm:grid-cols-1">
            <div class="col-span-1 rounded-2xl bg-navyBlue p-6 text-white">
                <p class="text-sm opacity-80">@lang('shop::app.customers.account.loyalty.current-balance')</p>
                <p class="mt-2 text-4xl font-bold">{{ number_format($balance) }}</p>
                <p class="mt-1 text-sm opacity-70">@lang('shop::app.customers.account.loyalty.points')</p>
                <p class="mt-3 text-sm opacity-90">
                    ≈ {{ core()->formatPrice($balance * (float)(core()->getConfigData('general.loyalty.settings.redeem_rate') ?? 0.10)) }}
                </p>
            </div>

            <div class="col-span-2 flex flex-col justify-center gap-3 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800">@lang('shop::app.customers.account.loyalty.how-it-works')</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li>🛍️ @lang('shop::app.customers.account.loyalty.earn-info', ['rate' => core()->getConfigData('general.loyalty.settings.earn_rate') ?? 10])</li>
                    <li>💰 @lang('shop::app.customers.account.loyalty.redeem-info', ['rate' => core()->formatPrice(core()->getConfigData('general.loyalty.settings.redeem_rate') ?? 0.10)])</li>
                    <li>🔑 @lang('shop::app.customers.account.loyalty.min-info', ['min' => core()->getConfigData('general.loyalty.settings.min_redeem') ?? 50])</li>
                </ul>
                <a href="{{ route('shop.checkout.cart.index') }}" class="secondary-button mt-2 w-max">
                    @lang('shop::app.customers.account.loyalty.go-shop')
                </a>
            </div>
        </div>

        <!-- Transactions -->
        <h3 class="mb-4 mt-8 text-lg font-semibold text-gray-800">
            @lang('shop::app.customers.account.loyalty.history')
        </h3>

        @if ($transactions->isEmpty())
            <p class="rounded-xl border border-dashed border-gray-200 py-10 text-center text-sm text-gray-400">
                @lang('shop::app.customers.account.loyalty.no-transactions')
            </p>
        @else
            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-500">
                        <tr>
                            <th class="px-4 py-3 text-left">@lang('shop::app.customers.account.loyalty.date')</th>
                            <th class="px-4 py-3 text-left">@lang('shop::app.customers.account.loyalty.description')</th>
                            <th class="px-4 py-3 text-right">@lang('shop::app.customers.account.loyalty.points')</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($transactions as $tx)
                            <tr class="bg-white">
                                <td class="px-4 py-3 text-gray-500">{{ $tx->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $tx->description }}</td>
                                <td class="px-4 py-3 text-right font-semibold {{ $tx->points > 0 ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $tx->points > 0 ? '+' : '' }}{{ number_format($tx->points) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-shop::layouts.account>
