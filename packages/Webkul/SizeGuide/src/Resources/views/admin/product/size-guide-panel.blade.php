@php
    $charts = app(\Webkul\SizeGuide\Repositories\SizeChartRepository::class)->all();
    $assigned = app(\Webkul\SizeGuide\Repositories\SizeChartRepository::class)->assignedChartId($product->id);
@endphp

<div class="box-shadow rounded bg-white p-4 dark:bg-gray-900" id="size-guide-panel">
    <p class="mb-4 flex items-center justify-between text-base font-semibold text-gray-800 dark:text-white">
        @lang('size-guide::app.admin.size-guide.assign')
    </p>

    <select id="sg-chart-select"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-blue-500 focus:outline-none">
        <option value="">@lang('size-guide::app.admin.size-guide.no-guide')</option>
        @foreach($charts as $chart)
            <option value="{{ $chart->id }}" {{ $assigned == $chart->id ? 'selected' : '' }}>
                {{ $chart->name }} ({{ ucfirst($chart->gender) }} / {{ ucfirst($chart->type) }})
            </option>
        @endforeach
    </select>

    <button type="button" id="sg-assign-btn"
            class="mt-3 w-full rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
        @lang('size-guide::app.admin.size-guide.save')
    </button>

    <p id="sg-feedback" class="mt-2 hidden text-xs text-green-600 dark:text-green-400">
        @lang('size-guide::app.admin.size-guide.assigned')
    </p>
</div>

@pushOnce('scripts')
<script>
document.addEventListener('click', function(e) {
    if (e.target.id !== 'sg-assign-btn') return;

    var chartId = document.getElementById('sg-chart-select').value;
    var productId = {{ $product->id }};
    var xsrf = (document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]*)/) || [])[1];
    xsrf = xsrf ? decodeURIComponent(xsrf) : '';

    e.target.disabled = true;

    fetch('{{ route('admin.size-guide.assign-product') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-XSRF-TOKEN': xsrf,
        },
        body: JSON.stringify({ product_id: productId, chart_id: chartId || null }),
    })
    .then(function(r) { return r.json(); })
    .then(function() {
        var fb = document.getElementById('sg-feedback');
        fb.classList.remove('hidden');
        setTimeout(function() { fb.classList.add('hidden'); }, 2500);
    })
    .finally(function() {
        e.target.disabled = false;
    });
});
</script>
@endPushOnce
