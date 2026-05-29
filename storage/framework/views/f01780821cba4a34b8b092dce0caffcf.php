<?php $__env->startSection('title'); ?>
    Bosta Shipments
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">Bosta Shipments</p>
    </div>

    <div class="mt-6 box-shadow rounded-xl bg-white dark:bg-gray-900">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="bg-gray-50 dark:bg-gray-700 text-xs text-gray-700 dark:text-gray-300 uppercase">
                <tr>
                    <th class="px-6 py-3">Order</th>
                    <th class="px-6 py-3">Bosta ID</th>
                    <th class="px-6 py-3">Tracking #</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Created</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $deliveries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-6 py-4">
                            <a href="<?php echo e(route('admin.sales.orders.view', $d->order_id)); ?>" class="text-navyBlue hover:underline font-medium">
                                #<?php echo e($d->order_number); ?>

                            </a>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs"><?php echo e($d->bosta_id ?? '—'); ?></td>
                        <td class="px-6 py-4">
                            <?php if($d->tracking_number): ?>
                                <span class="font-mono font-bold"><?php echo e($d->tracking_number); ?></span>
                                <a
                                    href="https://bosta.co/track/<?php echo e($d->tracking_number); ?>"
                                    target="_blank"
                                    class="ml-2 text-xs text-navyBlue hover:underline"
                                >Track</a>
                            <?php else: ?>
                                <span class="text-gray-400">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php
                                $statusColor = match($d->status) {
                                    'created' => 'bg-green-100 text-green-700',
                                    'failed'  => 'bg-red-100 text-red-600',
                                    default   => 'bg-gray-100 text-gray-500',
                                };
                            ?>
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium <?php echo e($statusColor); ?>">
                                <?php echo e(ucfirst($d->status)); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4"><?php echo e(\Carbon\Carbon::parse($d->created_at)->format('d M Y')); ?></td>
                        <td class="px-6 py-4">
                            <?php if(! $d->tracking_number): ?>
                                <button
                                    onclick="createShipment(<?php echo e($d->order_id); ?>, this)"
                                    class="text-sm text-navyBlue hover:underline"
                                >Create Shipment</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-400">No Bosta shipments yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="p-4">
            <?php echo e($deliveries->links()); ?>

        </div>
    </div>

    <?php if (! $__env->hasRenderedOnce('969207fe-d802-42a8-bba9-d9f3a27c584f')): $__env->markAsRenderedOnce('969207fe-d802-42a8-bba9-d9f3a27c584f');
$__env->startPush('scripts'); ?>
        <script>
        function createShipment(orderId, btn) {
            btn.disabled = true;
            btn.textContent = 'Creating...';
            fetch(`<?php echo e(url('admin/bosta/orders')); ?>/${orderId}/create-shipment`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' },
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    btn.textContent = '✓ ' + (d.tracking_number || 'Created');
                    btn.className = 'text-sm text-green-600';
                } else {
                    btn.textContent = 'Failed';
                    btn.className = 'text-sm text-red-500';
                    btn.disabled = false;
                }
            })
            .catch(() => { btn.textContent = 'Error'; btn.disabled = false; });
        }
        </script>
    <?php $__env->stopPush(); endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\aven\packages\Webkul\Bosta\src\Resources\views\admin\index.blade.php ENDPATH**/ ?>