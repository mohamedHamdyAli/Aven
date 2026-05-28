<?php $__env->startSection('page_title', 'My Store Credit'); ?>

<?php $__env->startSection('content-wrapper'); ?>
<div class="container mx-auto max-w-4xl px-4 py-10">

    <h1 class="mb-6 text-2xl font-bold text-gray-900">Store Credit</h1>

    
    <div class="mb-8 rounded-2xl bg-gradient-to-r from-indigo-500 to-purple-600 p-6 text-white shadow">
        <p class="text-sm opacity-80">Available Balance</p>
        <p class="mt-1 text-4xl font-bold"><?php echo e(core()->formatPrice($balance)); ?></p>
        <p class="mt-2 text-xs opacity-70">Credit is automatically applied at checkout when you choose to use it.</p>
    </div>

    
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
        <div class="border-b border-gray-100 px-5 py-4">
            <p class="font-semibold text-gray-800">Transaction History</p>
        </div>
        <?php if($transactions->isEmpty()): ?>
            <p class="py-12 text-center text-sm text-gray-400">No transactions yet.</p>
        <?php else: ?>
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50 text-xs text-gray-500">
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Description</th>
                        <th class="px-4 py-3 text-right">Amount</th>
                        <th class="px-4 py-3 text-right">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-3 text-xs text-gray-400"><?php echo e($tx->created_at->format('d M Y')); ?></td>
                            <td class="px-4 py-3 text-gray-600"><?php echo e($tx->note ?? ucfirst($tx->type)); ?></td>
                            <td class="px-4 py-3 text-right font-semibold <?php echo e($tx->type === 'credit' ? 'text-green-600' : 'text-red-600'); ?>">
                                <?php echo e($tx->type === 'credit' ? '+' : '-'); ?><?php echo e(core()->formatPrice($tx->amount)); ?>

                            </td>
                            <td class="px-4 py-3 text-right text-gray-500"><?php echo e(core()->formatPrice($tx->balance_after)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <div class="p-4"><?php echo e($transactions->links()); ?></div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('shop::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\aven\packages\Webkul\Wallet\src\Resources\views\shop\index.blade.php ENDPATH**/ ?>