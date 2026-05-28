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
     <?php $__env->slot('title', null, []); ?> Store Credit / Wallets <?php $__env->endSlot(); ?>

    <div class="flex items-center justify-between gap-4">
        <p class="text-xl font-bold text-gray-800">Store Credit / Wallets</p>
    </div>

    <?php if(session('success')): ?>
        <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    
    <div class="mt-6 rounded-xl border border-gray-200 bg-white p-5">
        <p class="mb-4 text-sm font-semibold text-gray-700">Issue Store Credit</p>
        <form method="POST" action="<?php echo e(route('admin.wallet.issue')); ?>" class="flex flex-wrap items-end gap-3">
            <?php echo csrf_field(); ?>
            <div>
                <label class="mb-1 block text-xs text-gray-500">Customer ID</label>
                <input type="number" name="customer_id" class="w-36 rounded-lg border border-gray-300 px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="mb-1 block text-xs text-gray-500">Amount</label>
                <input type="number" name="amount" step="0.01" min="0.01" class="w-32 rounded-lg border border-gray-300 px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="mb-1 block text-xs text-gray-500">Note</label>
                <input type="text" name="note" class="w-64 rounded-lg border border-gray-300 px-3 py-2 text-sm" placeholder="e.g. Refund, Loyalty bonus">
            </div>
            <button type="submit" class="primary-button">Issue Credit</button>
        </form>
    </div>

    
    <div class="mt-6 overflow-hidden rounded-xl border border-gray-200 bg-white">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50 text-xs text-gray-500">
                    <th class="px-4 py-3 text-left font-semibold">Customer</th>
                    <th class="px-4 py-3 text-left font-semibold">Email</th>
                    <th class="px-4 py-3 text-right font-semibold">Balance</th>
                    <th class="px-4 py-3 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $wallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wallet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php $c = \Webkul\Customer\Models\Customer::find($wallet->customer_id) ?>
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800"><?php echo e($c?->first_name); ?> <?php echo e($c?->last_name); ?></td>
                        <td class="px-4 py-3 text-gray-500"><?php echo e($c?->email); ?></td>
                        <td class="px-4 py-3 text-right font-semibold text-green-700"><?php echo e(number_format($wallet->balance, 2)); ?></td>
                        <td class="px-4 py-3 text-right">
                            <a href="<?php echo e(route('admin.wallet.customer', $wallet->customer_id)); ?>"
                               class="text-xs text-indigo-600 hover:underline">History</a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="py-16 text-center text-sm text-gray-400">No wallet balances yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4"><?php echo e($wallets->links()); ?></div>
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
<?php /**PATH D:\aven\packages\Webkul\Wallet\src\Resources\views\admin\index.blade.php ENDPATH**/ ?>