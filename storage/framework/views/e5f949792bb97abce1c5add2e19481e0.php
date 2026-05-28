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
     <?php $__env->slot('title', null, []); ?> Affiliates <?php $__env->endSlot(); ?>

    <div class="flex items-center justify-between gap-4">
        <p class="text-xl font-bold text-gray-800">Affiliates</p>
    </div>

    <?php if(session('success')): ?>
        <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="mt-6 overflow-hidden rounded-xl border border-gray-200 bg-white">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50 text-xs text-gray-500">
                    <th class="px-4 py-3 text-left">Name / Email</th>
                    <th class="px-4 py-3 text-left">Code</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-right">Rate</th>
                    <th class="px-4 py-3 text-right">Earned</th>
                    <th class="px-4 py-3 text-right">Clicks</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $affiliates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800"><?php echo e($a->name); ?></p>
                            <p class="text-xs text-gray-400"><?php echo e($a->email); ?></p>
                        </td>
                        <td class="px-4 py-3 font-mono text-xs text-indigo-700"><?php echo e($a->code); ?></td>
                        <td class="px-4 py-3 text-center">
                            <span class="rounded-full px-2 py-0.5 text-xs font-semibold
                                <?php echo e($a->status === 'approved' ? 'bg-green-100 text-green-700' :
                                   ($a->status === 'pending'  ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-600')); ?>">
                                <?php echo e(ucfirst($a->status)); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3 text-right text-gray-600"><?php echo e($a->commission_rate); ?>%</td>
                        <td class="px-4 py-3 text-right font-medium text-green-700"><?php echo e(number_format($a->total_earned, 2)); ?></td>
                        <td class="px-4 py-3 text-right text-gray-500"><?php echo e($a->clicks_count); ?></td>
                        <td class="px-4 py-3 text-right">
                            <a href="<?php echo e(route('admin.affiliates.show', $a->id)); ?>" class="mr-2 text-xs text-indigo-600 hover:underline">View</a>
                            <?php if($a->status === 'pending'): ?>
                                <form method="POST" action="<?php echo e(route('admin.affiliates.approve', $a->id)); ?>" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button class="mr-1 text-xs text-green-600 hover:underline">Approve</button>
                                </form>
                                <form method="POST" action="<?php echo e(route('admin.affiliates.reject', $a->id)); ?>" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button class="text-xs text-red-500 hover:underline">Reject</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="py-16 text-center text-sm text-gray-400">No affiliates yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4"><?php echo e($affiliates->links()); ?></div>
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
<?php /**PATH D:\aven\packages\Webkul\Affiliate\src\Resources\views\admin\index.blade.php ENDPATH**/ ?>