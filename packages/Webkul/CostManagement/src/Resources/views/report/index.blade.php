<x-admin::layouts>
    <x-slot:title>Profit & Loss Report</x-slot>

    {{-- ── Header + Date Filter ── --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <p class="text-xl font-bold text-gray-800 dark:text-white">P&amp;L Dashboard</p>
            <button type="button" class="primary-button py-1.5 text-sm" @click="$refs.adSpendModal.open()">
                + Log Ad Spend
            </button>
        </div>

        <form method="GET" action="{{ route('admin.cost_management.report.index') }}" class="flex items-center gap-3 flex-wrap">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">From</label>
                <input type="date" name="from" value="{{ $from }}"
                       class="rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">To</label>
                <input type="date" name="to" value="{{ $to }}"
                       class="rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white">
            </div>
            <button type="submit" class="primary-button py-2">Apply</button>
            <div class="flex gap-2">
                @foreach ([
                    'This Month' => [now()->startOfMonth()->toDateString(), now()->toDateString()],
                    'Last Month' => [now()->subMonth()->startOfMonth()->toDateString(), now()->subMonth()->endOfMonth()->toDateString()],
                    'This Year'  => [now()->startOfYear()->toDateString(), now()->toDateString()],
                ] as $label => $range)
                    <a href="{{ route('admin.cost_management.report.index', ['from' => $range[0], 'to' => $range[1]]) }}"
                       class="rounded-lg border px-3 py-1.5 text-xs font-medium
                              {{ $from === $range[0] && $to === $range[1] ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-300 text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </form>
    </div>

    {{-- ── KPI Cards ── --}}
    <div id="kpi-cards" class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-5">
        @for ($i = 0; $i < 5; $i++)
            <div class="animate-pulse rounded-xl border border-gray-200 bg-gray-100 dark:bg-gray-800 p-5 h-28"></div>
        @endfor
    </div>

    {{-- ── Gross bar ── --}}
    <div id="gross-strip" class="mt-4 rounded-xl border border-gray-200 bg-white dark:bg-gray-900 p-5">
        <div class="animate-pulse h-10 bg-gray-100 dark:bg-gray-800 rounded-lg"></div>
    </div>

    {{-- ── Chart + Top Products ── --}}
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-5">
        <div class="lg:col-span-3 rounded-xl border border-gray-200 bg-white dark:bg-gray-900 p-5">
            <p class="mb-4 text-sm font-semibold text-gray-700 dark:text-gray-200">Revenue vs Profit — Last 12 Months</p>
            <div id="chart-skeleton" class="animate-pulse h-48 bg-gray-100 dark:bg-gray-800 rounded-lg"></div>
            <canvas id="pl-chart" height="200" class="hidden"></canvas>
        </div>

        <div class="lg:col-span-2 rounded-xl border border-gray-200 bg-white dark:bg-gray-900 p-5">
            <p class="mb-4 text-sm font-semibold text-gray-700 dark:text-gray-200">Top 10 Products by Profit</p>
            <div id="top-products-skeleton" class="space-y-2">
                @for ($i = 0; $i < 5; $i++)
                    <div class="animate-pulse h-8 bg-gray-100 dark:bg-gray-800 rounded"></div>
                @endfor
            </div>
            <div id="top-products-list" class="space-y-3 overflow-y-auto max-h-72 hidden"></div>
        </div>
    </div>

    {{-- ── Full P&L Waterfall + Shipping ── --}}
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- Waterfall P&L --}}
        <div class="rounded-xl border border-gray-200 bg-white dark:bg-gray-900 p-5">
            <p class="mb-4 text-sm font-semibold text-gray-700 dark:text-gray-200">Full P&amp;L Statement</p>
            <div id="pl-summary-skeleton" class="animate-pulse space-y-2">
                @for ($i = 0; $i < 8; $i++)
                    <div class="h-7 bg-gray-100 dark:bg-gray-800 rounded"></div>
                @endfor
            </div>
            <table id="pl-summary" class="w-full text-sm hidden">
                <tbody id="pl-summary-body"></tbody>
            </table>
        </div>

        {{-- Shipping Analysis --}}
        <div class="rounded-xl border border-gray-200 bg-white dark:bg-gray-900 p-5">
            <p class="mb-4 text-sm font-semibold text-gray-700 dark:text-gray-200">Shipping Analysis</p>
            <div id="shipping-skeleton" class="animate-pulse space-y-2">
                @for ($i = 0; $i < 4; $i++)
                    <div class="h-7 bg-gray-100 dark:bg-gray-800 rounded"></div>
                @endfor
            </div>
            <div id="shipping-body" class="hidden space-y-0"></div>
        </div>
    </div>

    {{-- ── Shareholder Overview ── --}}
    <div id="shareholders-section" class="mt-6 rounded-xl border border-gray-200 bg-white dark:bg-gray-900 p-5">
        <div class="mb-4 flex items-center justify-between">
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Shareholder Overview</p>
            <a href="{{ route('admin.cost_management.shareholders.index') }}"
               class="text-xs text-blue-600 hover:underline">Manage →</a>
        </div>
        <div id="sh-skeleton" class="animate-pulse space-y-2">
            @for ($i = 0; $i < 3; $i++)
                <div class="h-10 bg-gray-100 dark:bg-gray-800 rounded"></div>
            @endfor
        </div>
        <div id="sh-body" class="hidden"></div>
    </div>

    {{-- ── Transaction Feed ── --}}
    <div class="mt-6 rounded-xl border border-gray-200 bg-white dark:bg-gray-900">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 px-5 py-4">
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Transaction Feed</p>
            <span class="text-xs text-gray-400">Ad spend and logged transactions in selected period</span>
        </div>
        <div id="tx-skeleton" class="p-5 space-y-2">
            @for ($i = 0; $i < 5; $i++)
                <div class="animate-pulse h-10 bg-gray-100 dark:bg-gray-800 rounded"></div>
            @endfor
        </div>
        <div id="tx-empty" class="hidden py-12 text-center text-sm text-gray-400">
            No transactions in this period.
        </div>
        <div id="tx-table-wrap" class="hidden overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 text-xs text-gray-500">
                        <th class="px-4 py-2.5 text-left font-semibold">Date</th>
                        <th class="px-4 py-2.5 text-left font-semibold">Type</th>
                        <th class="px-4 py-2.5 text-left font-semibold">Description</th>
                        <th class="px-4 py-2.5 text-left font-semibold">Platform</th>
                        <th class="px-4 py-2.5 text-right font-semibold">Amount</th>
                    </tr>
                </thead>
                <tbody id="tx-body"></tbody>
            </table>
        </div>
    </div>

    {{-- ── Ad Spend Modal ── --}}
    <x-admin::modal ref="adSpendModal">
        <x-slot:header><p class="text-lg font-semibold">Log Ad Spend</p></x-slot>
        <x-slot:content>
            <form id="ad-spend-form" method="POST" action="{{ route('admin.cost_management.ad_spend.store') }}">
                @csrf
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Platform *</label>
                        <select name="platform" required class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white px-4 py-2.5 text-sm">
                            @foreach ($platforms as $p)
                                <option value="{{ $p }}">{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Amount *</label>
                        <input type="number" step="0.01" min="0.01" name="amount" required
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white px-4 py-2.5 text-sm"
                               placeholder="0.00">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Campaign / Description *</label>
                        <input type="text" name="description" required
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white px-4 py-2.5 text-sm"
                               placeholder="e.g. Facebook — Summer Sale Campaign">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Date *</label>
                        <input type="date" name="transaction_date" required value="{{ date('Y-m-d') }}"
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white px-4 py-2.5 text-sm">
                    </div>
                </div>
            </form>
        </x-slot>
        <x-slot:footer>
            <button type="submit" form="ad-spend-form" class="primary-button">Save Ad Spend</button>
        </x-slot>
    </x-admin::modal>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
    <script>
    (function () {
        const from       = '{{ $from }}';
        const to         = '{{ $to }}';
        const dataUrl    = '{{ route('admin.cost_management.report.data') }}';
        const csrfToken  = '{{ csrf_token() }}';

        function fmt(n) {
            return 'L.E ' + Math.abs(n).toLocaleString('en-EG', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
        function colorCls(n) { return n >= 0 ? 'text-green-600' : 'text-red-600'; }
        function hideSkel(id) { document.getElementById(id)?.classList.add('hidden'); }

        // ── KPI Cards ──────────────────────────────────────────────────────────
        function renderKpi(s) {
            const cards = [
                { label: 'Gross Revenue',    value: s.revenue,      color: 'indigo', icon: '💰', sub: s.orders_count + ' orders' },
                { label: 'Net Revenue',      value: s.net_revenue,  color: 'blue',   icon: '🔄', sub: 'After refunds ' + fmt(s.refunds) },
                { label: 'Cost of Goods',    value: s.cogs,         color: 'red',    icon: '📦', sub: 'Product + Shipping' },
                { label: 'Expenses',         value: s.expenses + s.ad_spend, color: 'orange', icon: '🧾', sub: 'General + Ad Spend' },
                { label: 'Net Profit',       value: s.net_profit,   color: s.net_profit >= 0 ? 'green' : 'red', icon: '📊', sub: s.margin + '% margin' },
            ];
            const cm = {
                indigo: 'bg-indigo-50 border-indigo-200 text-indigo-700',
                blue:   'bg-blue-50 border-blue-200 text-blue-700',
                red:    'bg-red-50 border-red-200 text-red-700',
                orange: 'bg-orange-50 border-orange-200 text-orange-700',
                green:  'bg-green-50 border-green-200 text-green-700',
            };
            document.getElementById('kpi-cards').innerHTML = cards.map(c => `
                <div class="rounded-xl border ${cm[c.color]} p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-600">${c.label}</p>
                        <span class="text-xl">${c.icon}</span>
                    </div>
                    <p class="mt-2 text-xl font-bold ${colorCls(c.value)}">${fmt(Math.abs(c.value))}</p>
                    <p class="mt-1 text-xs text-gray-500">${c.sub}</p>
                </div>
            `).join('');

            // Gross strip
            const costsW  = s.net_revenue > 0 ? Math.min(100, Math.round(((s.cogs + s.expenses) / s.net_revenue) * 100)) : 0;
            const profitW = Math.max(0, 100 - costsW);
            document.getElementById('gross-strip').innerHTML = `
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs text-gray-500">Gross Profit (Net Revenue − COGS)</p>
                        <p class="text-2xl font-bold ${colorCls(s.gross_profit)}">${fmt(s.gross_profit)}</p>
                    </div>
                    <div class="w-full max-w-md">
                        <div class="mb-1 flex justify-between text-xs text-gray-500">
                            <span>Costs ${costsW}%</span><span>Profit ${profitW}%</span>
                        </div>
                        <div class="flex h-4 w-full overflow-hidden rounded-full bg-gray-100">
                            <div class="bg-red-400 transition-all" style="width:${costsW}%"></div>
                            <div class="bg-green-500 transition-all" style="width:${profitW}%"></div>
                        </div>
                    </div>
                </div>
            `;
        }

        // ── Full P&L Waterfall ─────────────────────────────────────────────────
        function renderPL(s) {
            const row = (label, val, cls = '', extra = '') =>
                `<tr class="border-b border-gray-50 dark:border-gray-800 ${extra}">
                    <td class="py-2 text-sm text-gray-600 dark:text-gray-300 pl-${extra.includes('indent') ? '6' : '0'}">${label}</td>
                    <td class="py-2 text-right text-sm font-semibold ${cls || colorCls(val)}">${fmt(val)}</td>
                </tr>`;

            const divider = (label) =>
                `<tr class="bg-gray-50 dark:bg-gray-800">
                    <td colspan="2" class="py-1 px-1 text-xs font-bold text-gray-400 uppercase tracking-wide">${label}</td>
                </tr>`;

            document.getElementById('pl-summary-body').innerHTML = [
                divider('Revenue'),
                row('Gross Revenue', s.revenue, 'text-gray-900'),
                row('− Returns & Refunds', -s.refunds, 'text-red-500'),
                `<tr class="border-b-2 border-gray-200 dark:border-gray-700 font-bold">
                    <td class="py-2 text-sm">= Net Revenue</td>
                    <td class="py-2 text-right text-sm font-bold ${colorCls(s.net_revenue)}">${fmt(s.net_revenue)}</td>
                </tr>`,
                divider('Cost of Goods Sold'),
                row('  Product Costs', -s.product_cogs, 'text-red-500'),
                row('  Shipping Costs (per unit)', -s.shipping_cogs, 'text-red-500'),
                `<tr class="border-b-2 border-gray-200 dark:border-gray-700 font-bold">
                    <td class="py-2 text-sm">= Gross Profit</td>
                    <td class="py-2 text-right text-sm font-bold ${colorCls(s.gross_profit)}">${fmt(s.gross_profit)}</td>
                </tr>`,
                divider('Operating Expenses'),
                row('  General Expenses', -s.expenses, 'text-orange-500'),
                row('  Ad Spend', -s.ad_spend, 'text-purple-500'),
                `<tr class="bg-gray-50 dark:bg-gray-800 font-bold text-base">
                    <td class="py-3 text-sm font-bold">= Net Profit</td>
                    <td class="py-3 text-right text-sm font-bold ${colorCls(s.net_profit)}">${fmt(s.net_profit)} <span class="text-xs font-normal text-gray-400">(${s.margin}%)</span></td>
                </tr>`,
            ].join('');

            hideSkel('pl-summary-skeleton');
            document.getElementById('pl-summary').classList.remove('hidden');
        }

        // ── Shipping Analysis ──────────────────────────────────────────────────
        function renderShipping(s) {
            hideSkel('shipping-skeleton');
            const el    = document.getElementById('shipping-body');
            const net   = s.shipping_net;
            const rows  = [
                ['Collected from customers', s.shipping_collected, 'text-green-600'],
                ['Cost per unit (COGS)', -s.shipping_cogs, 'text-red-500'],
                ['Net shipping P&L', net, net >= 0 ? 'text-green-700 font-bold' : 'text-red-700 font-bold'],
            ];
            el.innerHTML = `
                <table class="w-full text-sm">
                    <tbody>
                        ${rows.map(([l, v, c]) => `
                        <tr class="border-b border-gray-50 dark:border-gray-800">
                            <td class="py-2 text-gray-600 dark:text-gray-300">${l}</td>
                            <td class="py-2 text-right ${c}">${fmt(v)}</td>
                        </tr>`).join('')}
                    </tbody>
                </table>
                <div class="mt-4 rounded-lg ${net >= 0 ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'} px-4 py-3 text-sm">
                    Shipping is ${net >= 0 ? 'contributing <strong>' + fmt(net) + '</strong> profit' : 'costing <strong>' + fmt(Math.abs(net)) + '</strong> extra'} in this period.
                </div>
            `;
            el.classList.remove('hidden');
        }

        // ── Shareholders ───────────────────────────────────────────────────────
        function renderShareholders(sh) {
            hideSkel('sh-skeleton');
            const el = document.getElementById('sh-body');

            if (!sh.shareholders.length) {
                el.innerHTML = '<p class="text-sm text-gray-400 text-center py-6">No shareholders configured yet.</p>';
                el.classList.remove('hidden');
                return;
            }

            const summaryCards = [
                ['Total Share Capital', sh.total_capital],
                ['Cash Contributed', sh.total_contributed],
                ['Profit Distributed', sh.total_distributed],
            ];

            el.innerHTML = `
                <div class="mb-4 grid grid-cols-3 gap-3">
                    ${summaryCards.map(([l, v]) => `
                    <div class="rounded-lg bg-gray-50 dark:bg-gray-800 p-3 text-center">
                        <p class="text-xs text-gray-500">${l}</p>
                        <p class="text-base font-bold text-gray-800 dark:text-white">${fmt(v)}</p>
                    </div>`).join('')}
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-800 text-xs text-gray-400 font-semibold">
                                <th class="py-2 text-left">Shareholder</th>
                                <th class="py-2 text-right">Shares</th>
                                <th class="py-2 text-right">Ownership</th>
                                <th class="py-2 text-right">Share Value</th>
                                <th class="py-2 text-right">Cash In</th>
                                <th class="py-2 text-right">Earned</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${sh.shareholders.map(s => `
                            <tr class="border-b border-gray-50 dark:border-gray-800">
                                <td class="py-2 font-medium text-gray-800 dark:text-white">${s.name}</td>
                                <td class="py-2 text-right text-gray-600">${s.shares.toLocaleString()}</td>
                                <td class="py-2 text-right font-semibold text-blue-600">${s.pct}%</td>
                                <td class="py-2 text-right text-gray-600">${fmt(s.investment)}</td>
                                <td class="py-2 text-right text-green-600">${fmt(s.contributed)}</td>
                                <td class="py-2 text-right font-semibold text-green-700">${fmt(s.earned)}</td>
                            </tr>`).join('')}
                        </tbody>
                    </table>
                </div>
            `;
            el.classList.remove('hidden');
        }

        // ── Chart ──────────────────────────────────────────────────────────────
        function renderChart(monthly) {
            hideSkel('chart-skeleton');
            document.getElementById('pl-chart').classList.remove('hidden');
            new Chart(document.getElementById('pl-chart'), {
                type: 'bar',
                data: {
                    labels: monthly.map(m => m.label),
                    datasets: [
                        { label: 'Revenue',     data: monthly.map(m => m.revenue), backgroundColor: 'rgba(99,102,241,0.7)', borderRadius: 4, order: 2 },
                        { label: 'Total Costs', data: monthly.map(m => m.costs),   backgroundColor: 'rgba(239,68,68,0.5)',  borderRadius: 4, order: 3 },
                        { label: 'Net Profit',  data: monthly.map(m => m.profit),  type: 'line', borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.1)', borderWidth: 2, pointBackgroundColor: '#10b981', tension: 0.3, fill: true, order: 1 },
                    ],
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'top' } },
                    scales: { y: { ticks: { callback: v => v.toLocaleString() } } },
                },
            });
        }

        // ── Top Products ───────────────────────────────────────────────────────
        function renderTopProducts(products) {
            const list = document.getElementById('top-products-list');
            hideSkel('top-products-skeleton');
            if (!products.length) {
                list.innerHTML = '<p class="text-sm text-gray-400 text-center mt-8">No orders in this period.</p>';
                list.classList.remove('hidden');
                return;
            }
            list.innerHTML = products.map((p, i) => {
                const margin = p.revenue > 0 ? Math.round((p.profit / p.revenue) * 100 * 10) / 10 : 0;
                return `
                <div class="flex items-center gap-3">
                    <span class="w-5 flex-shrink-0 text-xs font-bold text-gray-400">${i + 1}</span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-xs font-medium text-gray-800 dark:text-white">${p.product_name}</p>
                        <p class="text-xs text-gray-400">${p.units_sold} units · ${fmt(p.revenue)}</p>
                    </div>
                    <span class="flex-shrink-0 text-xs font-bold ${p.profit >= 0 ? 'text-green-600' : 'text-red-600'}">
                        ${fmt(p.profit)} <span class="font-normal text-gray-400">(${margin}%)</span>
                    </span>
                </div>`;
            }).join('');
            list.classList.remove('hidden');
        }

        // ── Transactions ───────────────────────────────────────────────────────
        function renderTransactions(transactions) {
            hideSkel('tx-skeleton');
            if (!transactions.length) {
                document.getElementById('tx-empty').classList.remove('hidden');
                return;
            }
            const typeMap = {
                sale:     { label: 'Sale',     cls: 'bg-green-100 text-green-700' },
                refund:   { label: 'Refund',   cls: 'bg-red-100 text-red-700' },
                ad_spend: { label: 'Ad Spend', cls: 'bg-purple-100 text-purple-700' },
                expense:  { label: 'Expense',  cls: 'bg-orange-100 text-orange-700' },
            };
            document.getElementById('tx-body').innerHTML = transactions.map(tx => {
                const tc  = typeMap[tx.type] ?? { label: tx.type, cls: 'bg-gray-100 text-gray-700' };
                const del = tx.destroy_url
                    ? `<button class="ml-2 text-xs text-red-400 hover:text-red-600"
                           onclick="if(confirm('Delete?')) fetch('${tx.destroy_url}',{method:'DELETE',headers:{'X-CSRF-TOKEN':'${csrfToken}'}}).then(()=>location.reload())">✕</button>`
                    : '';
                return `
                <tr class="border-b border-gray-50 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="px-4 py-2.5 text-gray-500 whitespace-nowrap text-xs">${tx.transaction_date}</td>
                    <td class="px-4 py-2.5"><span class="rounded-full px-2 py-0.5 text-xs font-semibold ${tc.cls}">${tc.label}</span></td>
                    <td class="px-4 py-2.5 text-gray-700 dark:text-gray-300 text-xs">${tx.description ?? ''}</td>
                    <td class="px-4 py-2.5 text-gray-400 text-xs">${tx.platform ?? '—'}</td>
                    <td class="px-4 py-2.5 text-right text-xs font-semibold whitespace-nowrap ${tx.amount >= 0 ? 'text-green-600' : 'text-red-600'}">
                        ${tx.amount >= 0 ? '+' : ''}${fmt(tx.amount)}${del}
                    </td>
                </tr>`;
            }).join('');
            document.getElementById('tx-table-wrap').classList.remove('hidden');
        }

        // ── Fetch all data ─────────────────────────────────────────────────────
        fetch(`${dataUrl}?from=${from}&to=${to}`)
            .then(r => r.json())
            .then(data => {
                renderKpi(data.stats);
                renderPL(data.stats);
                renderShipping(data.stats);
                renderShareholders(data.shareholders);
                renderChart(data.monthly);
                renderTopProducts(data.topProducts);
                renderTransactions(data.transactions);
            })
            .catch(() => {
                document.getElementById('kpi-cards').innerHTML =
                    '<div class="col-span-5 text-center text-sm text-red-500 py-8">Failed to load data. Please refresh.</div>';
            });
    })();
    </script>
    @endpush
</x-admin::layouts>
