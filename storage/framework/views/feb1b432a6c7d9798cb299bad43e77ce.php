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
     <?php $__env->slot('title', null, []); ?> Wallet — <?php echo e($customer->first_name); ?> <?php echo e($customer->last_name); ?> <?php $__env->endSlot(); ?>

    <div class="flex items-center justify-between gap-4">
        <p class="text-xl font-bold text-gray-800">
            Wallet: <?php echo e($customer->first_name); ?> <?php echo e($customer->last_name); ?>

        </p>
        <a href="<?php echo e(route('admin.wallet.index')); ?>" class="secondary-button">← Back</a>
    </div>

    <?php if(session('success')): ?>
        <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    
    <div class="mt-6 flex gap-4">
        <div class="rounded-xl border border-gray-200 bg-white px-6 py-5">
            <p class="text-xs text-gray-500">Current Balance</p>
            <p class="text-3xl font-bold text-green-600"><?php echo e(number_format($balance, 2)); ?></p>
        </div>
    </div>

    
    <div class="mt-6 grid gap-4 sm:grid-cols-2">
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="mb-3 text-sm font-semibold text-gray-700">Issue Credit</p>
            <form method="POST" action="<?php echo e(route('admin.wallet.issue')); ?>" class="space-y-3">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="customer_id" value="<?php echo e($customer->id); ?>">
                <input type="number" name="amount" step="0.01" min="0.01" placeholder="Amount"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required>
                <input type="text" name="note" placeholder="Note (optional)"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <button class="primary-button w-full justify-center">Issue Credit</button>
            </form>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="mb-3 text-sm font-semibold text-gray-700">Revoke Credit</p>
            <form method="POST" action="<?php echo e(route('admin.wallet.revoke')); ?>" class="space-y-3">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="customer_id" value="<?php echo e($customer->id); ?>">
                <input type="number" name="amount" step="0.01" min="0.01" placeholder="Amount"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required>
                <input type="text" name="note" placeholder="Note (optional)"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <button class="bg-red-600 hover:bg-red-700 text-white rounded-lg px-4 py-2 text-sm font-semibold w-full">Revoke Credit</button>
            </form>
        </div>
    </div>

    
    <div class="mt-6 overflow-hidden rounded-xl border border-gray-200 bg-white">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50 text-xs text-gray-500">
                    <th class="px-4 py-3 text-left">Date</th>
                    <th class="px-4 py-3 text-left">Type</th>
                    <th class="px-4 py-3 text-left">Note</th>
                    <th class="px-4 py-3 text-right">Amount</th>
                    <th class="px-4 py-3 text-right">Balance After</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3 text-xs text-gray-400"><?php echo e($tx->created_at->format('d M Y H:i')); ?></td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs font-semibold
                                <?php echo e($tx->type === 'credit' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'); ?>">
                                <?php echo e(ucfirst($tx->type)); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500"><?php echo e($tx->note ?? '—'); ?></td>
                        <td class="px-4 py-3 text-right font-semibold <?php echo e($tx->type === 'credit' ? 'text-green-700' : 'text-red-700'); ?>">
                            <?php echo e($tx->type === 'credit' ? '+' : '-'); ?><?php echo e(number_format($tx->amount, 2)); ?>

                        </td>
                        <td class="px-4 py-3 text-right text-gray-600"><?php echo e(number_format($tx->balance_after, 2)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="py-12 text-center text-sm text-gray-400">No transactions.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4"><?php echo e($transactions->links()); ?></div>
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
<?php /**PATH D:\aven\packages\Webkul\Wallet\src\Resources\views\admin\customer.blade.php ENDPATH**/ ?>