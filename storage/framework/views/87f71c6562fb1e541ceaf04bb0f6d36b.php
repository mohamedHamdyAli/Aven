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
     <?php $__env->slot('title', null, []); ?> Edit Expense <?php $__env->endSlot(); ?>

    <div class="flex items-center justify-between gap-4">
        <p class="text-xl font-bold text-gray-800">Edit Expense</p>
        <a href="<?php echo e(route('admin.cost_management.expenses.index')); ?>" class="secondary-button">← Back</a>
    </div>

    <div class="mt-6 max-w-2xl rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <form method="POST" action="<?php echo e(route('admin.cost_management.expenses.update', $expense->id)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="<?php echo e(old('title', $expense->title)); ?>" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Category</label>
                    <select name="category" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e($expense->category === $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Amount</label>
                    <input type="number" step="0.01" min="0" name="amount" value="<?php echo e(old('amount', $expense->amount)); ?>" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="expense_date" value="<?php echo e(old('expense_date', $expense->expense_date->format('Y-m-d'))); ?>" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Recurring?</label>
                    <select name="is_recurring" id="is-recurring-sel" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                        <option value="0" <?php echo e(! $expense->is_recurring ? 'selected' : ''); ?>>No — One-time</option>
                        <option value="1" <?php echo e($expense->is_recurring ? 'selected' : ''); ?>>Yes — Recurring</option>
                    </select>
                </div>

                <div id="frequency-row" class="<?php echo e($expense->is_recurring ? '' : 'hidden'); ?>">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Frequency</label>
                    <select name="frequency" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                        <?php $__currentLoopData = ['monthly', 'weekly', 'yearly']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $freq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($freq); ?>" <?php echo e($expense->frequency === $freq ? 'selected' : ''); ?>><?php echo e(ucfirst($freq)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" rows="3" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm"><?php echo e(old('notes', $expense->notes)); ?></textarea>
                </div>
            </div>

            <div class="mt-6 flex gap-4">
                <button type="submit" class="primary-button">Update Expense</button>
                <a href="<?php echo e(route('admin.cost_management.expenses.index')); ?>" class="secondary-button">Cancel</a>
            </div>
        </form>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        document.getElementById('is-recurring-sel').addEventListener('change', function () {
            document.getElementById('frequency-row').classList.toggle('hidden', this.value === '0');
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
<?php /**PATH D:\aven\packages\Webkul\CostManagement\src\Resources\views\expenses\edit.blade.php ENDPATH**/ ?>