<x-admin::layouts>
    <x-slot:title>
        {{ $campaign->campaign_name }}
    </x-slot>

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $campaign->campaign_name }}</p>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $campaign->cart_rule_name }}
                &nbsp;·&nbsp;
                @if ($campaign->action_type === 'by_percent')
                    {{ number_format($campaign->discount_amount, 0) }}% @lang('admin::app.marketing.promotions.coupon-assignments.show.off')
                @else
                    {{ number_format($campaign->discount_amount, 2) }} EGP
                @endif
                &nbsp;·&nbsp;
                {{ \Carbon\Carbon::parse($campaign->created_at)->format('d M Y') }}
            </p>
        </div>

        <a href="{{ route('admin.marketing.promotions.coupon_assignments.index') }}" class="secondary-button">
            @lang('admin::app.marketing.promotions.coupon-assignments.back')
        </a>
    </div>

    {{-- Stats --}}
    @php
        $total    = $assignments->count();
        $used     = $assignments->where('times_used', '>', 0)->count();
        $notUsed  = $total - $used;
    @endphp

    <div class="mt-6 grid grid-cols-3 gap-4">
        <div class="box-shadow rounded bg-white p-4 text-center dark:bg-gray-900">
            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $total }}</p>
            <p class="text-sm text-gray-500">@lang('admin::app.marketing.promotions.coupon-assignments.show.total')</p>
        </div>
        <div class="box-shadow rounded bg-white p-4 text-center dark:bg-gray-900">
            <p class="text-2xl font-bold text-green-600">{{ $used }}</p>
            <p class="text-sm text-gray-500">@lang('admin::app.marketing.promotions.coupon-assignments.datagrid.used-yes')</p>
        </div>
        <div class="box-shadow rounded bg-white p-4 text-center dark:bg-gray-900">
            <p class="text-2xl font-bold text-blue-500">{{ $notUsed }}</p>
            <p class="text-sm text-gray-500">@lang('admin::app.marketing.promotions.coupon-assignments.datagrid.used-no')</p>
        </div>
    </div>

    {{-- Assignments Table --}}
    <div class="mt-6 box-shadow rounded bg-white dark:bg-gray-900">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <p class="font-semibold text-gray-700 dark:text-gray-200">
                @lang('admin::app.marketing.promotions.coupon-assignments.show.coupons-list')
            </p>
            <input
                type="text"
                id="search-input"
                placeholder="@lang('admin::app.marketing.promotions.coupon-assignments.show.search')"
                oninput="filterTable(this.value)"
                class="rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm text-gray-700 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300"
            >
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="assignments-table">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400">#</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400">
                            @lang('admin::app.marketing.promotions.coupon-assignments.datagrid.phone')
                        </th>
                        <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400">
                            @lang('admin::app.marketing.promotions.coupon-assignments.datagrid.coupon-code')
                        </th>
                        <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400">
                            @lang('admin::app.marketing.promotions.coupon-assignments.datagrid.used')
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($assignments as $i => $a)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-6 py-3 text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-6 py-3 font-mono text-gray-800 dark:text-white">{{ $a->phone }}</td>
                        <td class="px-6 py-3">
                            <span class="rounded bg-blue-50 px-2 py-0.5 font-mono font-semibold tracking-widest text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                {{ $a->coupon_code }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            @if ($a->times_used > 0)
                                <span class="label-active">
                                    @lang('admin::app.marketing.promotions.coupon-assignments.datagrid.used-yes')
                                </span>
                            @else
                                <span class="label-info">
                                    @lang('admin::app.marketing.promotions.coupon-assignments.datagrid.used-no')
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($assignments->isEmpty())
                <div class="px-6 py-12 text-center text-sm text-gray-400">
                    @lang('admin::app.marketing.promotions.coupon-assignments.show.empty')
                </div>
            @endif
        </div>
    </div>

    @pushOnce('scripts')
    <script>
        function filterTable(q) {
            q = q.toLowerCase();
            document.querySelectorAll('#assignments-table tbody tr').forEach(tr => {
                tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
            });
        }
    </script>
    @endPushOnce
</x-admin::layouts>
