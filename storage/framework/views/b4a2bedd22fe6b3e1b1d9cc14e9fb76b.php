<?php if (isset($component)) { $__componentOriginal8001c520f4b7dcb40a16cd3b411856d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8001c520f4b7dcb40a16cd3b411856d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.layouts.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin::layouts'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?> Profit & Loss Report <?php $__env->endSlot(); ?>

    <!-- Header + Date Filter -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <p class="text-xl font-bold text-gray-800">Profit &amp; Loss Dashboard</p>
            <button type="button" id="log-ad-spend-btn" class="primary-button py-1.5 text-sm" @click="$refs.adSpendModal.open()">
                + Log Ad Spend
            </button>
        </div>

        <form id="filter-form" method="GET" action="<?php echo e(route('admin.cost_management.report.index')); ?>" class="flex items-center gap-3 flex-wrap">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-600">From</label>
                <input type="date" name="from" value="<?php echo e($from); ?>"
                       class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-600">To</label>
                <input type="date" name="to" value="<?php echo e($to); ?>"
                       class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
            </div>
            <button type="submit" class="primary-button py-2">Apply</button>

            <!-- Quick Ranges -->
            <div class="flex gap-2">
                <?php $__currentLoopData = [
                    'This Month' => [now()->startOfMonth()->toDateString(), now()->toDateString()],
                    'Last Month' => [now()->subMonth()->startOfMonth()->toDateString(), now()->subMonth()->endOfMonth()->toDateString()],
                    'This Year'  => [now()->startOfYear()->toDateString(), now()->toDateString()],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $range): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('admin.cost_management.report.index', ['from' => $range[0], 'to' => $range[1]])); ?>"
                       class="rounded-lg border px-3 py-1.5 text-xs font-medium
                              <?php echo e($from === $range[0] && $to === $range[1] ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-300 text-gray-600 hover:bg-gray-50'); ?>">
                        <?php echo e($label); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </form>
    </div>

    <!-- KPI Cards Skeleton → filled by JS -->
    <div id="kpi-cards" class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-5">
        <?php for($i = 0; $i < 5; $i++): ?>
            <div class="animate-pulse rounded-xl border border-gray-200 bg-gray-100 p-5 h-28"></div>
        <?php endfor; ?>
    </div>

    <!-- Gross Profit Strip Skeleton -->
    <div id="gross-strip" class="mt-4 rounded-xl border border-gray-200 bg-white p-5">
        <div class="animate-pulse h-10 bg-gray-100 rounded-lg"></div>
    </div>

    <!-- Chart + Top Products Skeleton -->
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-5">
        <div id="chart-wrap" class="lg:col-span-3 rounded-xl border border-gray-200 bg-white p-5">
            <p class="mb-4 text-sm font-semibold text-gray-700">Revenue vs Profit — Last 12 Months</p>
            <div id="chart-skeleton" class="animate-pulse h-48 bg-gray-100 rounded-lg"></div>
            <canvas id="pl-chart" height="200" class="hidden"></canvas>
        </div>

        <div id="top-products" class="lg:col-span-2 rounded-xl border border-gray-200 bg-white p-5">
            <p class="mb-4 text-sm font-semibold text-gray-700">Top 10 Products by Profit</p>
            <div id="top-products-skeleton" class="space-y-2">
                <?php for($i = 0; $i < 5; $i++): ?>
                    <div class="animate-pulse h-8 bg-gray-100 rounded"></div>
                <?php endfor; ?>
            </div>
            <div id="top-products-list" class="space-y-3 overflow-y-auto max-h-72 hidden"></div>
        </div>
    </div>

    <!-- P&L Summary + Quick Actions -->
    <div id="summary-section" class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="mb-4 text-sm font-semibold text-gray-700">P&amp;L Summary</p>
            <div id="pl-summary-skeleton" class="animate-pulse space-y-2">
                <?php for($i = 0; $i < 5; $i++): ?>
                    <div class="h-8 bg-gray-100 rounded"></div>
                <?php endfor; ?>
            </div>
            <table id="pl-summary" class="w-full text-sm hidden">
                <tbody id="pl-summary-body"></tbody>
            </table>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="mb-4 text-sm font-semibold text-gray-700">Quick Actions</p>
            <div class="space-y-3">
                <a href="<?php echo e(route('admin.cost_management.products.index')); ?>"
                   class="flex items-center gap-3 rounded-lg border border-gray-200 p-3 hover:bg-gray-50">
                    <span class="text-2xl">📦</span>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Manage Product Costs</p>
                        <p class="text-xs text-gray-500">Enter purchase price, manufacturing fee, shipping per unit</p>
                    </div>
                </a>
                <a href="<?php echo e(route('admin.cost_management.expenses.index')); ?>"
                   class="flex items-center gap-3 rounded-lg border border-gray-200 p-3 hover:bg-gray-50">
                    <span class="text-2xl">🧾</span>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Manage General Expenses</p>
                        <p class="text-xs text-gray-500">Rent, salaries, marketing, utilities, and other overheads</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Transaction Feed -->
    <div class="mt-6 rounded-xl border border-gray-200 bg-white">
        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
            <p class="text-sm font-semibold text-gray-700">Transaction Feed</p>
            <span class="text-xs text-gray-400">Auto-logged sales, refunds &amp; ad spend in selected period</span>
        </div>

        <div id="tx-skeleton" class="p-5 space-y-2">
            <?php for($i = 0; $i < 5; $i++): ?>
                <div class="animate-pulse h-10 bg-gray-100 rounded"></div>
            <?php endfor; ?>
        </div>

        <div id="tx-empty" class="hidden py-12 text-center text-sm text-gray-400">
            No transactions recorded yet in this period.<br>
            <span class="text-xs">Sales and refunds are auto-logged when they happen.</span>
        </div>

        <div id="tx-table-wrap" class="hidden overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50 text-xs text-gray-500">
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

    <!-- Ad Spend Modal -->
    <?php if (isset($component)) { $__componentOriginal09768308838b828c7799162f44758281 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal09768308838b828c7799162f44758281 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.modal.index','data' => ['ref' => 'adSpendModal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin::modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['ref' => 'adSpendModal']); ?>
         <?php $__env->slot('header', null, []); ?> 
            <p class="text-lg font-semibold">📣 Log Ad Spend</p>
         <?php $__env->endSlot(); ?>

         <?php $__env->slot('content', null, []); ?> 
            <form id="ad-spend-form" method="POST" action="<?php echo e(route('admin.cost_management.ad_spend.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Platform <span class="text-red-500">*</span></label>
                        <select name="platform" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                            <?php $__currentLoopData = $platforms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($p); ?>"><?php echo e($p); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Amount Spent <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" min="0.01" name="amount" required
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm"
                               placeholder="0.00">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Campaign / Description <span class="text-red-500">*</span></label>
                        <input type="text" name="description" required
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm"
                               placeholder="e.g. Facebook — Summer Sale Campaign">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Date <span class="text-red-500">*</span></label>
                        <input type="date" name="transaction_date" required value="<?php echo e(date('Y-m-d')); ?>"
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                    </div>
                </div>
            </form>
         <?php $__env->endSlot(); ?>

         <?php $__env->slot('footer', null, []); ?> 
            <button type="submit" form="ad-spend-form" class="primary-button">Save Ad Spend</button>
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal09768308838b828c7799162f44758281)): ?>
<?php $attributes = $__attributesOriginal09768308838b828c7799162f44758281; ?>
<?php unset($__attributesOriginal09768308838b828c7799162f44758281); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal09768308838b828c7799162f44758281)): ?>
<?php $component = $__componentOriginal09768308838b828c7799162f44758281; ?>
<?php unset($__componentOriginal09768308838b828c7799162f44758281); ?>
<?php endif; ?>

    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
    <script>
    (function () {
        const from = '<?php echo e($from); ?>';
        const to   = '<?php echo e($to); ?>';
        const dataUrl = '<?php echo e(route('admin.cost_management.report.data')); ?>';
        const csrfToken = '<?php echo e(csrf_token()); ?>';

        function fmt(n) {
            return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(n);
        }

        function colorClass(n) {
            return n >= 0 ? 'text-green-600' : 'text-red-600';
        }

        function renderKpi(stats) {
            const cards = [
                { label: 'Total Revenue',    value: stats.revenue,    color: 'indigo', icon: '💰', sub: stats.orders_count + ' orders' },
                { label: 'Cost of Goods',    value: stats.cogs,       color: 'red',    icon: '📦', sub: 'Refunds: ' + fmt(stats.refunds) },
                { label: 'Ad Spend',         value: stats.ad_spend,   color: 'purple', icon: '📣', sub: 'Marketing campaigns' },
                { label: 'General Expenses', value: stats.expenses,   color: 'orange', icon: '🧾', sub: 'Rent, salaries, etc.' },
                { label: 'Net Profit',       value: stats.net_profit, color: stats.net_profit >= 0 ? 'green' : 'red', icon: '📊', sub: stats.margin + '% margin' },
            ];

            const cm = {
                indigo: 'bg-indigo-50 border-indigo-200 text-indigo-700',
                red:    'bg-red-50 border-red-200 text-red-700',
                purple: 'bg-purple-50 border-purple-200 text-purple-700',
                orange: 'bg-orange-50 border-orange-200 text-orange-700',
                green:  'bg-green-50 border-green-200 text-green-700',
            };

            document.getElementById('kpi-cards').innerHTML = cards.map(c => `
                <div class="rounded-xl border ${cm[c.color]} p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-600">${c.label}</p>
                        <span class="text-2xl">${c.icon}</span>
                    </div>
                    <p class="mt-2 text-2xl font-bold">
                        ${fmt(Math.abs(c.value))}
                        ${c.value < 0 ? '<span class="text-base font-normal">(Loss)</span>' : ''}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">${c.sub}</p>
                </div>
            `).join('');

            // Gross strip
            const totalCosts = stats.cogs + stats.expenses;
            const costsW = stats.revenue > 0 ? Math.min(100, Math.round((totalCosts / stats.revenue) * 100)) : 0;
            const profitW = Math.max(0, 100 - costsW);

            document.getElementById('gross-strip').innerHTML = `
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Gross Profit (Revenue − COGS)</p>
                        <p class="text-xl font-bold ${colorClass(stats.gross_profit)}">${fmt(stats.gross_profit)}</p>
                    </div>
                    <div class="w-full max-w-md">
                        <div class="mb-1 flex justify-between text-xs text-gray-500">
                            <span>Costs ${costsW}%</span>
                            <span>Profit ${profitW}%</span>
                        </div>
                        <div class="flex h-4 w-full overflow-hidden rounded-full bg-gray-100">
                            <div class="bg-red-400 transition-all" style="width:${costsW}%"></div>
                            <div class="bg-green-500 transition-all" style="width:${profitW}%"></div>
                        </div>
                    </div>
                </div>
            `;

            // P&L summary
            const rows = [
                ['Total Revenue', fmt(stats.revenue), 'text-gray-900', ''],
                ['− Cost of Goods (COGS)', '− ' + fmt(stats.cogs), 'text-red-600', 'border-b border-gray-100'],
                ['Gross Profit', fmt(stats.gross_profit), colorClass(stats.gross_profit), 'border-b border-gray-200 font-semibold'],
                ['− General Expenses', '− ' + fmt(stats.expenses), 'text-orange-600', 'border-b border-gray-100'],
                ['Net Profit', fmt(stats.net_profit) + ' <span class="text-xs font-normal text-gray-500">(' + stats.margin + '%)</span>', colorClass(stats.net_profit), 'bg-gray-50 font-bold text-base'],
            ];

            document.getElementById('pl-summary-body').innerHTML = rows.map(r => `
                <tr class="border-b border-gray-100 ${r[3]}">
                    <td class="py-2 text-gray-600">${r[0]}</td>
                    <td class="py-2 text-right ${r[2]}">${r[1]}</td>
                </tr>
            `).join('');

            document.getElementById('pl-summary-skeleton').classList.add('hidden');
            document.getElementById('pl-summary').classList.remove('hidden');
        }

        function renderChart(monthly) {
            document.getElementById('chart-skeleton').classList.add('hidden');
            document.getElementById('pl-chart').classList.remove('hidden');

            new Chart(document.getElementById('pl-chart'), {
                type: 'bar',
                data: {
                    labels: monthly.map(m => m.label),
                    datasets: [
                        { label: 'Revenue', data: monthly.map(m => m.revenue), backgroundColor: 'rgba(99,102,241,0.7)', borderRadius: 4, order: 2 },
                        { label: 'Total Costs', data: monthly.map(m => m.costs), backgroundColor: 'rgba(239,68,68,0.5)', borderRadius: 4, order: 3 },
                        { label: 'Net Profit', data: monthly.map(m => m.profit), type: 'line', borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.1)', borderWidth: 2, pointBackgroundColor: '#10b981', tension: 0.3, fill: true, order: 1 },
                    ],
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'top' } },
                    scales: { y: { ticks: { callback: v => v.toLocaleString() } } },
                },
            });
        }

        function renderTopProducts(products) {
            const list = document.getElementById('top-products-list');
            document.getElementById('top-products-skeleton').classList.add('hidden');

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
                            <p class="truncate text-xs font-medium text-gray-800">${p.product_name}</p>
                            <p class="text-xs text-gray-400">${p.units_sold} units · ${fmt(p.revenue)}</p>
                        </div>
                        <span class="flex-shrink-0 text-xs font-bold ${p.profit >= 0 ? 'text-green-600' : 'text-red-600'}">
                            ${fmt(p.profit)} <span class="font-normal text-gray-400">(${margin}%)</span>
                        </span>
                    </div>
                `;
            }).join('');
            list.classList.remove('hidden');
        }

        function renderTransactions(transactions) {
            const skeleton  = document.getElementById('tx-skeleton');
            const empty     = document.getElementById('tx-empty');
            const tableWrap = document.getElementById('tx-table-wrap');
            const tbody     = document.getElementById('tx-body');

            skeleton.classList.add('hidden');

            if (!transactions.length) {
                empty.classList.remove('hidden');
                return;
            }

            const typeMap = {
                sale:     { label: 'Sale',     cls: 'bg-green-100 text-green-700' },
                refund:   { label: 'Refund',   cls: 'bg-red-100 text-red-700' },
                ad_spend: { label: 'Ad Spend', cls: 'bg-purple-100 text-purple-700' },
                expense:  { label: 'Expense',  cls: 'bg-orange-100 text-orange-700' },
            };

            tbody.innerHTML = transactions.map(tx => {
                const tc = typeMap[tx.type] ?? { label: tx.type, cls: 'bg-gray-100 text-gray-700' };
                const amountClass = tx.amount >= 0 ? 'text-green-600' : 'text-red-600';
                const sign = tx.amount >= 0 ? '+' : '';
                const deleteBtn = tx.destroy_url
                    ? `<button type="button" class="ml-2 text-xs text-red-400 hover:text-red-600"
                           onclick="if(confirm('Delete this ad spend?')) fetch('${tx.destroy_url}',{method:'DELETE',headers:{'X-CSRF-TOKEN':'${csrfToken}'}}).then(()=>location.reload())">✕</button>`
                    : '';
                return `
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-2.5 text-gray-500 whitespace-nowrap">${tx.transaction_date}</td>
                        <td class="px-4 py-2.5"><span class="rounded-full px-2 py-0.5 text-xs font-semibold ${tc.cls}">${tc.label}</span></td>
                        <td class="px-4 py-2.5 text-gray-700">${tx.description ?? ''}</td>
                        <td class="px-4 py-2.5 text-gray-400 text-xs">${tx.platform ?? '—'}</td>
                        <td class="px-4 py-2.5 text-right font-semibold whitespace-nowrap ${amountClass}">
                            ${sign}${fmt(tx.amount)}${deleteBtn}
                        </td>
                    </tr>
                `;
            }).join('');

            tableWrap.classList.remove('hidden');
        }

        // Fetch all data async — page shows instantly
        fetch(`${dataUrl}?from=${from}&to=${to}`)
            .then(r => r.json())
            .then(data => {
                renderKpi(data.stats);
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
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8001c520f4b7dcb40a16cd3b411856d1)): ?>
<?php $attributes = $__attributesOriginal8001c520f4b7dcb40a16cd3b411856d1; ?>
<?php unset($__attributesOriginal8001c520f4b7dcb40a16cd3b411856d1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8001c520f4b7dcb40a16cd3b411856d1)): ?>
<?php $component = $__componentOriginal8001c520f4b7dcb40a16cd3b411856d1; ?>
<?php unset($__componentOriginal8001c520f4b7dcb40a16cd3b411856d1); ?>
<?php endif; ?>
<?php /**PATH D:\aven\packages\Webkul\CostManagement\src\Resources\views\report\index.blade.php ENDPATH**/ ?>