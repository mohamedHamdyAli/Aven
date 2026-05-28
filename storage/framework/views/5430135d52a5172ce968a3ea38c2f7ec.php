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
     <?php $__env->slot('title', null, []); ?> Affiliate: <?php echo e($affiliate->name); ?> <?php $__env->endSlot(); ?>

    <div class="flex items-center justify-between gap-4">
        <p class="text-xl font-bold text-gray-800"><?php echo e($affiliate->name); ?></p>
        <a href="<?php echo e(route('admin.affiliates.index')); ?>" class="secondary-button">← Back</a>
    </div>

    <?php if(session('success')): ?>
        <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    
    <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <p class="text-xs text-gray-500">Code</p>
            <p class="mt-1 font-mono text-lg font-bold text-indigo-700"><?php echo e($affiliate->code); ?></p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <p class="text-xs text-gray-500">Commission Rate</p>
            <p class="mt-1 text-2xl font-bold text-gray-800"><?php echo e($affiliate->commission_rate); ?>%</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <p class="text-xs text-gray-500">Total Earned</p>
            <p class="mt-1 text-2xl font-bold text-green-600"><?php echo e(number_format($affiliate->total_earned, 2)); ?></p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <p class="text-xs text-gray-500">Total Paid</p>
            <p class="mt-1 text-2xl font-bold text-gray-600"><?php echo e(number_format($affiliate->total_paid, 2)); ?></p>
        </div>
    </div>

    
    <div class="mt-4 rounded-xl border border-gray-100 bg-indigo-50 p-4 text-sm">
        <p class="mb-1 text-xs text-gray-500">Affiliate Link</p>
        <code class="text-indigo-700"><?php echo e(url('/ref/' . $affiliate->code)); ?></code>
    </div>

    
    <div class="mt-6 rounded-xl border border-gray-200 bg-white p-5">
        <p class="mb-3 text-sm font-semibold text-gray-700">Record Payment</p>
        <form method="POST" action="<?php echo e(route('admin.affiliates.mark-paid')); ?>" class="flex items-end gap-3">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="affiliate_id" value="<?php echo e($affiliate->id); ?>">
            <div>
                <label class="mb-1 block text-xs text-gray-500">Amount</label>
                <input type="number" name="amount" step="0.01" min="0.01" required
                       class="w-36 rounded-lg border border-gray-300 px-3 py-2 text-sm">
            </div>
            <button class="primary-button">Mark Paid & Approve All</button>
        </form>
    </div>

    
    <div class="mt-6 overflow-hidden rounded-xl border border-gray-200 bg-white">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50 text-xs text-gray-500">
                    <th class="px-4 py-3 text-left">Order</th>
                    <th class="px-4 py-3 text-right">Order Total</th>
                    <th class="px-4 py-3 text-right">Commission</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-right">Date</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $commissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-600">#<?php echo e($c->order_id); ?></td>
                        <td class="px-4 py-3 text-right"><?php echo e(number_format($c->order_total, 2)); ?></td>
                        <td class="px-4 py-3 text-right font-semibold text-green-700"><?php echo e(number_format($c->commission, 2)); ?></td>
                        <td class="px-4 py-3 text-center">
                            <span class="rounded-full px-2 py-0.5 text-xs font-semibold
                                <?php echo e($c->status === 'paid' ? 'bg-green-100 text-green-700' :
                                   ($c->status === 'approved' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700')); ?>">
                                <?php echo e(ucfirst($c->status)); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3 text-right text-xs text-gray-400"><?php echo e($c->created_at->format('d M Y')); ?></td>
                        <td class="px-4 py-3 text-right">
                            <?php if($c->status === 'pending'): ?>
                                <button onclick="fetch('<?php echo e(route('admin.affiliates.commission.approve', $c->id)); ?>',{method:'POST',headers:{'X-CSRF-TOKEN':'<?php echo e(csrf_token()); ?>'}}).then(()=>location.reload())"
                                        class="text-xs text-green-600 hover:underline">Approve</button>
                            <?php else: ?>
                                <span class="text-xs text-gray-300">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="py-10 text-center text-sm text-gray-400">No commissions.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4"><?php echo e($commissions->links()); ?></div>
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
<?php /**PATH D:\aven\packages\Webkul\Affiliate\src\Resources\views\admin\show.blade.php ENDPATH**/ ?>