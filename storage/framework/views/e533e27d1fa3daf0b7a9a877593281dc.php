<?php $__env->startSection('title'); ?>
    Gift Cards
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">Gift Cards</p>

        <a
            href="<?php echo e(route('admin.gift-cards.create')); ?>"
            class="primary-button"
        >
            Create Gift Cards
        </a>
    </div>

    <div class="mt-6 box-shadow rounded-xl bg-white dark:bg-gray-900">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="bg-gray-50 dark:bg-gray-700 text-xs text-gray-700 dark:text-gray-300 uppercase">
                <tr>
                    <th class="px-6 py-3">Code</th>
                    <th class="px-6 py-3">Initial Balance</th>
                    <th class="px-6 py-3">Used</th>
                    <th class="px-6 py-3">Remaining</th>
                    <th class="px-6 py-3">Recipient</th>
                    <th class="px-6 py-3">Expires</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-6 py-4 font-mono font-bold text-gray-900 dark:text-white"><?php echo e($card->code); ?></td>
                        <td class="px-6 py-4"><?php echo e(core()->formatPrice($card->initial_balance)); ?></td>
                        <td class="px-6 py-4"><?php echo e(core()->formatPrice($card->used_amount)); ?></td>
                        <td class="px-6 py-4 font-semibold <?php echo e($card->remaining_balance > 0 ? 'text-green-600' : 'text-gray-400'); ?>">
                            <?php echo e(core()->formatPrice($card->remaining_balance)); ?>

                        </td>
                        <td class="px-6 py-4">
                            <?php if($card->recipient_email): ?>
                                <div><?php echo e($card->recipient_name ?? '-'); ?></div>
                                <div class="text-xs text-gray-400"><?php echo e($card->recipient_email); ?></div>
                            <?php else: ?>
                                <span class="text-gray-400">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4"><?php echo e($card->expires_at?->format('d M Y') ?? 'Never'); ?></td>
                        <td class="px-6 py-4">
                            <?php if(! $card->is_active): ?>
                                <span class="rounded-full bg-gray-100 text-gray-500 px-2 py-0.5 text-xs">Inactive</span>
                            <?php elseif($card->remaining_balance <= 0): ?>
                                <span class="rounded-full bg-gray-100 text-gray-500 px-2 py-0.5 text-xs">Redeemed</span>
                            <?php elseif($card->expires_at && $card->expires_at->isPast()): ?>
                                <span class="rounded-full bg-red-100 text-red-600 px-2 py-0.5 text-xs">Expired</span>
                            <?php else: ?>
                                <span class="rounded-full bg-green-100 text-green-700 px-2 py-0.5 text-xs font-semibold">Active</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <form
                                method="POST"
                                action="<?php echo e(route('admin.gift-cards.destroy', $card->id)); ?>"
                                onsubmit="return confirm('Delete this gift card?')"
                            >
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-500 hover:underline text-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-400">No gift cards yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="p-4">
            <?php echo e($cards->links()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\aven\packages\Webkul\GiftCard\src\Resources\views\admin\gift-cards\index.blade.php ENDPATH**/ ?>