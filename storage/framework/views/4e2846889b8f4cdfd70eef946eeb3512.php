<?php $__env->startSection('title'); ?>
    Flash Sales
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">Flash Sales</p>

        <a
            href="<?php echo e(route('admin.marketing.flash-sales.create')); ?>"
            class="primary-button"
        >
            Create Flash Sale
        </a>
    </div>

    <div class="mt-6 box-shadow rounded-xl bg-white dark:bg-gray-900">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="bg-gray-50 dark:bg-gray-700 text-xs text-gray-700 dark:text-gray-300 uppercase">
                <tr>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Discount</th>
                    <th class="px-6 py-3">Products</th>
                    <th class="px-6 py-3">Starts At</th>
                    <th class="px-6 py-3">Ends At</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white"><?php echo e($sale->name); ?></td>
                        <td class="px-6 py-4"><?php echo e($sale->discount_percent); ?>%</td>
                        <td class="px-6 py-4"><?php echo e($sale->products_count); ?></td>
                        <td class="px-6 py-4"><?php echo e($sale->starts_at->format('d M Y H:i')); ?></td>
                        <td class="px-6 py-4"><?php echo e($sale->ends_at->format('d M Y H:i')); ?></td>
                        <td class="px-6 py-4">
                            <?php if($sale->active): ?>
                                <span class="rounded-full bg-green-100 text-green-700 px-2 py-0.5 text-xs font-semibold">Active</span>
                            <?php elseif(now() > $sale->ends_at): ?>
                                <span class="rounded-full bg-gray-100 text-gray-500 px-2 py-0.5 text-xs">Expired</span>
                            <?php else: ?>
                                <span class="rounded-full bg-yellow-100 text-yellow-700 px-2 py-0.5 text-xs">Scheduled</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <form
                                method="POST"
                                action="<?php echo e(route('admin.marketing.flash-sales.destroy', $sale->id)); ?>"
                                onsubmit="return confirm('Delete this flash sale?')"
                            >
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-500 hover:underline text-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-400">No flash sales yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="p-4">
            <?php echo e($sales->links()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\aven\packages\Webkul\FlashSale\src\Resources\views\admin\flash-sales\index.blade.php ENDPATH**/ ?>