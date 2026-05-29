<?php
    $charts = app(\Webkul\SizeGuide\Repositories\SizeChartRepository::class)->all();
    $assigned = app(\Webkul\SizeGuide\Repositories\SizeChartRepository::class)->assignedChartId($product->id);
?>

<div class="box-shadow rounded bg-white p-4 dark:bg-gray-900" id="size-guide-panel">
    <p class="mb-4 flex items-center justify-between text-base font-semibold text-gray-800 dark:text-white">
        <?php echo app('translator')->get('size-guide::app.admin.size-guide.assign'); ?>
    </p>

    <select id="sg-chart-select"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-blue-500 focus:outline-none">
        <option value=""><?php echo app('translator')->get('size-guide::app.admin.size-guide.no-guide'); ?></option>
        <?php $__currentLoopData = $charts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($chart->id); ?>" <?php echo e($assigned == $chart->id ? 'selected' : ''); ?>>
                <?php echo e($chart->name); ?> (<?php echo e(ucfirst($chart->gender)); ?> / <?php echo e(ucfirst($chart->type)); ?>)
            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <button type="button" id="sg-assign-btn"
            class="mt-3 w-full rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
        <?php echo app('translator')->get('size-guide::app.admin.size-guide.save'); ?>
    </button>

    <p id="sg-feedback" class="mt-2 hidden text-xs text-green-600 dark:text-green-400">
        <?php echo app('translator')->get('size-guide::app.admin.size-guide.assigned'); ?>
    </p>
</div>

<?php if (! $__env->hasRenderedOnce('0ce61829-b6e5-4771-8c4d-2e3c5432218e')): $__env->markAsRenderedOnce('0ce61829-b6e5-4771-8c4d-2e3c5432218e');
$__env->startPush('scripts'); ?>
<script>
document.addEventListener('click', function(e) {
    if (e.target.id !== 'sg-assign-btn') return;

    var chartId = document.getElementById('sg-chart-select').value;
    var productId = <?php echo e($product->id); ?>;
    var xsrf = (document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]*)/) || [])[1];
    xsrf = xsrf ? decodeURIComponent(xsrf) : '';

    e.target.disabled = true;

    fetch('<?php echo e(route('admin.size-guide.assign-product')); ?>', {
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
<?php $__env->stopPush(); endif; ?>
<?php /**PATH D:\aven\packages\Webkul\SizeGuide\src\Resources\views\admin\product\size-guide-panel.blade.php ENDPATH**/ ?>