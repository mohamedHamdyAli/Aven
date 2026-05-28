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
     <?php $__env->slot('title', null, []); ?> <?php echo app('translator')->get('size-guide::app.admin.size-guide.title'); ?> <?php $__env->endSlot(); ?>

    <div class="flex items-center justify-between mb-6">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            <?php echo app('translator')->get('size-guide::app.admin.size-guide.title'); ?>
        </p>
        <a href="<?php echo e(route('admin.size-guide.create')); ?>"
           class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
            + <?php echo app('translator')->get('size-guide::app.admin.size-guide.create'); ?>
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700 dark:bg-green-900/20 dark:text-green-400">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800 text-left text-xs font-semibold uppercase text-gray-500">
                <tr>
                    <th class="px-5 py-3"><?php echo app('translator')->get('size-guide::app.admin.size-guide.name'); ?></th>
                    <th class="px-5 py-3"><?php echo app('translator')->get('size-guide::app.admin.size-guide.gender'); ?></th>
                    <th class="px-5 py-3"><?php echo app('translator')->get('size-guide::app.admin.size-guide.type'); ?></th>
                    <th class="px-5 py-3"><?php echo app('translator')->get('size-guide::app.admin.size-guide.rows'); ?></th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                <?php $__empty_1 = true; $__currentLoopData = $charts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-5 py-3 font-medium text-gray-800 dark:text-white"><?php echo e($chart->name); ?></td>
                        <td class="px-5 py-3 capitalize text-gray-600 dark:text-gray-400"><?php echo e($chart->gender); ?></td>
                        <td class="px-5 py-3 capitalize text-gray-600 dark:text-gray-400"><?php echo e($chart->type); ?></td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-400"><?php echo e($chart->rows_count); ?></td>
                        <td class="px-5 py-3 flex items-center gap-3 justify-end">
                            <a href="<?php echo e(route('admin.size-guide.edit', $chart->id)); ?>"
                               class="text-blue-600 hover:underline text-xs"><?php echo app('translator')->get('size-guide::app.admin.size-guide.edit'); ?></a>
                            <button class="delete-btn text-red-500 hover:underline text-xs"
                                    data-id="<?php echo e($chart->id); ?>">
                                <?php echo app('translator')->get('size-guide::app.admin.size-guide.delete'); ?>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-gray-400">
                            <?php echo app('translator')->get('size-guide::app.admin.size-guide.empty'); ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
    document.addEventListener('click', function(e) {
        if (!e.target.classList.contains('delete-btn')) return;
        if (!confirm('حذف هذا الجدول؟')) return;

        var id = e.target.dataset.id;
        window.axios.delete('/<?php echo e(config('app.admin_url', 'admin')); ?>/size-guide/' + id)
            .then(function() { location.reload(); });
    });
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
<?php /**PATH D:\aven\packages\Webkul\SizeGuide\src\Resources\views\admin\size-guide\index.blade.php ENDPATH**/ ?>