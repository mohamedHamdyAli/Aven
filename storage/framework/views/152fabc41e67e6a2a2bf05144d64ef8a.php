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
     <?php $__env->slot('title', null, []); ?> Distribution #<?php echo e($distribution->id); ?> <?php $__env->endSlot(); ?>

    <div class="flex flex-col gap-6 max-w-3xl">

        
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="<?php echo e(route('admin.cost_management.distributions.index')); ?>" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">← Back</a>
                <p class="text-xl font-bold text-gray-800 dark:text-white">Distribution #<?php echo e($distribution->id); ?></p>
            </div>
            <form method="POST" action="<?php echo e(route('admin.cost_management.distributions.destroy', $distribution->id)); ?>" onsubmit="return confirm('Delete this distribution?')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="rounded-lg border border-red-300 bg-red-50 px-4 py-2 text-sm text-red-600 hover:bg-red-100">Delete</button>
            </form>
        </div>

        
        <?php if(session('success')): ?>
            <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700 border border-green-200"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        
        <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-900">
            <div class="grid grid-cols-2 gap-6 sm:grid-cols-4">
                <div>
                    <p class="text-xs text-gray-400 uppercase mb-1">Period</p>
                    <p class="font-semibold text-gray-800 dark:text-white text-sm">
                        <?php echo e($distribution->period_from->format('M j, Y')); ?><br>
                        <span class="text-gray-400">to</span> <?php echo e($distribution->period_to->format('M j, Y')); ?>

                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase mb-1">Net Profit</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white"><?php echo e(core()->currency($distribution->net_profit)); ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase mb-1">Total Distributed</p>
                    <p class="text-2xl font-bold text-green-600"><?php echo e(core()->currency($distribution->total_distributed)); ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase mb-1">Created</p>
                    <p class="font-semibold text-gray-800 dark:text-white text-sm"><?php echo e($distribution->created_at->format('M j, Y')); ?></p>
                </div>
            </div>
            <?php if($distribution->notes): ?>
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <p class="text-xs text-gray-400 uppercase mb-1">Notes</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300"><?php echo e($distribution->notes); ?></p>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <p class="font-semibold text-gray-800 dark:text-white">Per-Shareholder Breakdown</p>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left">Shareholder</th>
                        <th class="px-5 py-3 text-left">Contact</th>
                        <th class="px-5 py-3 text-right">Share %</th>
                        <th class="px-5 py-3 text-right">Amount</th>
                        <th class="px-5 py-3 text-right">% of Net</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php $__currentLoopData = $distribution->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-blue-700 text-sm font-bold dark:bg-blue-900 dark:text-blue-300">
                                        <?php echo e(strtoupper(substr($item->shareholder?->name ?? '?', 0, 1))); ?>

                                    </div>
                                    <span class="font-medium text-gray-800 dark:text-white"><?php echo e($item->shareholder?->name ?? 'Deleted'); ?></span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-gray-500 text-xs">
                                <?php echo e($item->shareholder?->email); ?><br>
                                <?php echo e($item->shareholder?->phone); ?>

                            </td>
                            <td class="px-5 py-3 text-right">
                                <span class="rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                                    <?php echo e(number_format($item->percentage, 2)); ?>%
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right font-bold text-green-600 text-base">
                                <?php echo e(core()->currency($item->amount)); ?>

                            </td>
                            <td class="px-5 py-3 text-right text-gray-400 text-xs">
                                <?php if($distribution->net_profit > 0): ?>
                                    <?php echo e(number_format(($item->amount / $distribution->net_profit) * 100, 1)); ?>%
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot class="bg-gray-50 dark:bg-gray-800 font-semibold">
                    <tr>
                        <td colspan="3" class="px-5 py-3 text-right text-gray-600 dark:text-gray-300">Total</td>
                        <td class="px-5 py-3 text-right text-green-600"><?php echo e(core()->currency($distribution->total_distributed)); ?></td>
                        <td class="px-5 py-3 text-right text-gray-400 text-xs">
                            <?php if($distribution->net_profit > 0): ?>
                                <?php echo e(number_format(($distribution->total_distributed / $distribution->net_profit) * 100, 1)); ?>%
                            <?php endif; ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
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
<?php /**PATH D:\aven\packages\Webkul\CostManagement\src\Resources\views\distributions\show.blade.php ENDPATH**/ ?>