<x-admin::layouts>
    <x-slot:title>Balance Sheet — قائمة المركز المالي</x-slot>

    <div class="flex flex-col gap-6">

        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-xl font-bold text-gray-800 dark:text-white">Balance Sheet &mdash; قائمة المركز المالي</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">As of {{ $data['as_of'] }} &nbsp;·&nbsp; All-time cumulative</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.cost_management.report.index') }}"
                   class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200">
                    P&amp;L Report
                </a>
                <a href="{{ route('admin.cost_management.shareholders.index') }}"
                   class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200">
                    Shareholders
                </a>
            </div>
        </div>

        {{-- Equation banner --}}
        <div class="rounded-lg border border-blue-200 bg-blue-50 px-5 py-3 dark:border-blue-800 dark:bg-blue-950">
            <div class="flex flex-wrap items-center justify-center gap-6 text-center text-sm font-semibold text-blue-700 dark:text-blue-300">
                <div>
                    <p class="text-xs font-normal text-blue-500 uppercase tracking-wider">Total Assets</p>
                    <p class="text-xl">{{ core()->currency($data['total_assets']) }}</p>
                </div>
                <span class="text-2xl text-blue-400">=</span>
                <div>
                    <p class="text-xs font-normal text-blue-500 uppercase tracking-wider">Liabilities</p>
                    <p class="text-xl">{{ core()->currency(0) }}</p>
                </div>
                <span class="text-2xl text-blue-400">+</span>
                <div>
                    <p class="text-xs font-normal text-blue-500 uppercase tracking-wider">Total Equity</p>
                    <p class="text-xl {{ $data['total_equity'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ core()->currency($data['total_equity']) }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            {{-- ── ASSETS ────────────────────────────────────────────────────── --}}
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900">
                <div class="border-b border-gray-100 dark:border-gray-800 px-5 py-3">
                    <p class="font-bold text-gray-800 dark:text-white">Assets &mdash; الأصول</p>
                </div>

                <div class="divide-y divide-gray-50 dark:divide-gray-800">

                    {{-- Section: Current Assets --}}
                    <div class="px-5 py-2">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 py-2">Current Assets</p>

                        <div class="flex items-center justify-between py-2">
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Accounts Receivable</p>
                                <p class="text-xs text-gray-400">Pending / unpaid orders</p>
                            </div>
                            <p class="text-sm font-semibold text-gray-800 dark:text-white">{{ core()->currency($data['receivables']) }}</p>
                        </div>
                    </div>

                    {{-- Section: Non-current Assets --}}
                    <div class="px-5 py-2">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 py-2">Inventory</p>

                        <div class="flex items-center justify-between py-2">
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Inventory Value</p>
                                <p class="text-xs text-gray-400">Qty on hand × unit cost (excl. shipping)</p>
                            </div>
                            <p class="text-sm font-semibold text-gray-800 dark:text-white">{{ core()->currency($data['inventory_value']) }}</p>
                        </div>
                    </div>

                    {{-- Total Assets --}}
                    <div class="px-5 py-4 bg-gray-50 dark:bg-gray-800/50 rounded-b-lg">
                        <div class="flex items-center justify-between">
                            <p class="font-bold text-gray-800 dark:text-white">Total Assets</p>
                            <p class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ core()->currency($data['total_assets']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── EQUITY ───────────────────────────────────────────────────── --}}
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900">
                <div class="border-b border-gray-100 dark:border-gray-800 px-5 py-3">
                    <p class="font-bold text-gray-800 dark:text-white">Equity &mdash; حقوق الملكية</p>
                </div>

                <div class="divide-y divide-gray-50 dark:divide-gray-800">

                    <div class="px-5 py-2">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 py-2">Capital</p>

                        {{-- Share Capital --}}
                        <div class="flex items-center justify-between py-2">
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Share Capital</p>
                                <p class="text-xs text-gray-400">{{ number_format($data['total_shares']) }} shares × {{ core()->currency($data['share_price']) }}</p>
                            </div>
                            <p class="text-sm font-semibold text-gray-800 dark:text-white">{{ core()->currency($data['share_capital']) }}</p>
                        </div>

                        {{-- Contributed Capital --}}
                        <div class="flex items-center justify-between py-2">
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Capital Contributed</p>
                                <p class="text-xs text-gray-400">Actual cash deposited by shareholders</p>
                            </div>
                            <p class="text-sm font-semibold text-green-600 dark:text-green-400">{{ core()->currency($data['total_contributed']) }}</p>
                        </div>
                    </div>

                    <div class="px-5 py-2">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 py-2">Retained Earnings</p>

                        {{-- Cumulative Net Profit --}}
                        <div class="flex items-center justify-between py-2">
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Cumulative Net Profit</p>
                                <p class="text-xs text-gray-400">All-time P&amp;L (revenue − COGS − expenses)</p>
                            </div>
                            <p class="text-sm font-semibold {{ $data['retained_earnings'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ core()->currency($data['retained_earnings']) }}
                            </p>
                        </div>

                        {{-- Less: Distributions --}}
                        <div class="flex items-center justify-between py-2">
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Less: Distributions Paid</p>
                                <p class="text-xs text-gray-400">Profit paid out to shareholders</p>
                            </div>
                            <p class="text-sm font-semibold text-red-500 dark:text-red-400">({{ core()->currency($data['total_distributed']) }})</p>
                        </div>
                    </div>

                    {{-- Total Equity --}}
                    <div class="px-5 py-4 bg-gray-50 dark:bg-gray-800/50 rounded-b-lg">
                        <div class="flex items-center justify-between">
                            <p class="font-bold text-gray-800 dark:text-white">Total Equity</p>
                            <p class="text-lg font-bold {{ $data['total_equity'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ core()->currency($data['total_equity']) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── SHAREHOLDER EQUITY BREAKDOWN ──────────────────────────────────── --}}
        @if(count($data['shareholders']) > 0)
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900">
            <div class="border-b border-gray-100 dark:border-gray-800 px-5 py-3">
                <p class="font-bold text-gray-800 dark:text-white">Shareholder Equity Breakdown &mdash; حصص المساهمين</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800 text-xs text-gray-500 uppercase tracking-wider">
                            <th class="px-5 py-3 text-left">Shareholder</th>
                            <th class="px-4 py-3 text-right">Shares</th>
                            <th class="px-4 py-3 text-right">Ownership</th>
                            <th class="px-4 py-3 text-right">Share Capital</th>
                            <th class="px-4 py-3 text-right">Cash Contributed</th>
                            <th class="px-4 py-3 text-right">Retained Share</th>
                            <th class="px-4 py-3 text-right">Distributed</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700 dark:text-gray-300">Net Equity</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @foreach($data['shareholders'] as $sh)
                        <tr class="{{ $sh['active'] ? '' : 'opacity-50' }}">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-700 text-sm font-bold dark:bg-blue-900 dark:text-blue-300">
                                        {{ strtoupper(substr($sh['name'], 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-white">{{ $sh['name'] }}</p>
                                        @if(!$sh['active'])<p class="text-xs text-gray-400">Inactive</p>@endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right text-gray-700 dark:text-gray-300">{{ number_format($sh['shares']) }}</td>
                            <td class="px-4 py-3 text-right">
                                <span class="rounded-full bg-blue-50 px-2 py-0.5 text-xs font-semibold text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                                    {{ number_format($sh['pct'], 2) }}%
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right text-gray-700 dark:text-gray-300">{{ core()->currency($sh['share_capital']) }}</td>
                            <td class="px-4 py-3 text-right text-green-600 dark:text-green-400">{{ core()->currency($sh['contributed']) }}</td>
                            <td class="px-4 py-3 text-right {{ $sh['earnings'] >= 0 ? 'text-green-600' : 'text-red-500' }}">
                                {{ core()->currency($sh['earnings']) }}
                            </td>
                            <td class="px-4 py-3 text-right text-red-500 dark:text-red-400">({{ core()->currency($sh['distributed']) }})</td>
                            <td class="px-4 py-3 text-right font-bold {{ $sh['net_equity'] >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-red-600' }}">
                                {{ core()->currency($sh['net_equity']) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                            <td class="px-5 py-3 font-bold text-gray-800 dark:text-white">Total</td>
                            <td class="px-4 py-3 text-right font-bold text-gray-800 dark:text-white">{{ number_format($data['total_shares']) }}</td>
                            <td class="px-4 py-3 text-right font-bold text-gray-800 dark:text-white">100%</td>
                            <td class="px-4 py-3 text-right font-bold text-gray-800 dark:text-white">{{ core()->currency($data['share_capital']) }}</td>
                            <td class="px-4 py-3 text-right font-bold text-green-600">{{ core()->currency($data['total_contributed']) }}</td>
                            <td class="px-4 py-3 text-right font-bold {{ $data['retained_earnings'] >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ core()->currency($data['retained_earnings']) }}</td>
                            <td class="px-4 py-3 text-right font-bold text-red-500">({{ core()->currency($data['total_distributed']) }})</td>
                            <td class="px-4 py-3 text-right font-bold text-blue-600 dark:text-blue-400 text-base">{{ core()->currency($data['total_equity']) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif

        {{-- ── NOTES ───────────────────────────────────────────────────────────── --}}
        <div class="rounded-lg border border-gray-100 bg-gray-50 px-5 py-4 text-xs text-gray-400 dark:border-gray-800 dark:bg-gray-900/50">
            <p class="font-semibold text-gray-500 dark:text-gray-400 mb-2">Notes</p>
            <ul class="space-y-1 list-disc list-inside">
                <li>Inventory value = quantity on hand × (cost price + manufacturing fee + other costs). Shipping cost excluded as it is a period cost.</li>
                <li>Receivables = grand total of orders with status <em>pending</em> or <em>pending_payment</em>.</li>
                <li>Retained earnings = all-time net profit (revenue − refunds − COGS − operating expenses − ad spend).</li>
                <li>Per-shareholder retained share is proportional to ownership percentage.</li>
                <li>Liabilities are not tracked in this system — this sheet reflects equity only.</li>
            </ul>
        </div>

    </div>
</x-admin::layouts>
