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
     <?php $__env->slot('title', null, []); ?> Profit Distributions <?php $__env->endSlot(); ?>

    <div class="flex flex-col gap-6">

        
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xl font-bold text-gray-800 dark:text-white">Profit Distributions</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">History of distributed profits to shareholders</p>
            </div>
            <a href="<?php echo e(route('admin.cost_management.distributions.create')); ?>" class="primary-button">
                + New Distribution
            </a>
        </div>

        
        <?php if(session('success')): ?>
            <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700 border border-green-200"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900 overflow-hidden">
            <?php if($distributions->isEmpty()): ?>
                <div class="p-12 text-center text-gray-400">
                    No distributions yet. Click "+ New Distribution" to create one.
                </div>
            <?php else: ?>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800 text-xs text-gray-500 dark:text-gray-400 uppercase">
                        <tr>
                            <th class="px-5 py-3 text-left">#</th>
                            <th class="px-5 py-3 text-left">Period</th>
                            <th class="px-5 py-3 text-right">Net Profit</th>
                            <th class="px-5 py-3 text-right">Total Distributed</th>
                            <th class="px-5 py-3 text-left">Shareholders</th>
                            <th class="px-5 py-3 text-left">Notes</th>
                            <th class="px-5 py-3 text-left">Date</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <?php $__currentLoopData = $distributions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <td class="px-5 py-3 text-gray-400"><?php echo e($dist->id); ?></td>
                                <td class="px-5 py-3 font-medium text-gray-800 dark:text-white">
                                    <?php echo e($dist->period_from->format('M j, Y')); ?> – <?php echo e($dist->period_to->format('M j, Y')); ?>

                                </td>
                                <td class="px-5 py-3 text-right font-semibold text-gray-800 dark:text-white">
                                    <?php echo e(core()->currency($dist->net_profit)); ?>

                                </td>
                                <td class="px-5 py-3 text-right font-semibold text-green-600">
                                    <?php echo e(core()->currency($dist->total_distributed)); ?>

                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center gap-1">
                                        <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                                            <?php echo e($dist->items->count()); ?> shareholders
                                        </span>
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-gray-500 max-w-[160px] truncate"><?php echo e($dist->notes); ?></td>
                                <td class="px-5 py-3 text-gray-400 text-xs"><?php echo e($dist->created_at->format('M j, Y')); ?></td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <a href="<?php echo e(route('admin.cost_management.distributions.show', $dist->id)); ?>" class="text-blue-600 hover:underline text-xs">View</a>
                                        <form method="POST" action="<?php echo e(route('admin.cost_management.distributions.destroy', $dist->id)); ?>" onsubmit="return confirm('Delete this distribution?')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-red-500 hover:underline text-xs">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-700">
                    <?php echo e($distributions->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
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
<?php /**PATH D:\aven\packages\Webkul\CostManagement\src\Resources\views\distributions\index.blade.php ENDPATH**/ ?>