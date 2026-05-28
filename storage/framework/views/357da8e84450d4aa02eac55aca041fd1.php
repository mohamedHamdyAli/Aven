<?php
    $inp = 'w-full rounded border border-gray-300 px-1 py-1 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-xs focus:outline-none focus:border-blue-400';
    $n = "rows[$i]";
?>
<tr class="border-t border-gray-100 dark:border-gray-800">
    <td class="px-1 py-1">
        <input name="<?php echo e($n); ?>[label]" class="<?php echo e($inp); ?>" placeholder="S,M,L..."
               value="<?php echo e($row['label'] ?? ''); ?>" required>
    </td>
    <?php $__currentLoopData = ['eu_size','uk_size','us_size']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <td class="px-1 py-1">
        <input name="<?php echo e($n); ?>[<?php echo e($f); ?>]" class="<?php echo e($inp); ?>" placeholder="—"
               value="<?php echo e($row[$f] ?? ''); ?>">
    </td>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php $__currentLoopData = ['chest','waist','hips','height']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <td class="px-1 py-1">
        <input name="<?php echo e($n); ?>[<?php echo e($f); ?>_min]" type="number" step="0.1" class="<?php echo e($inp); ?>" placeholder="min"
               value="<?php echo e($row[$f.'_min'] ?? ''); ?>">
    </td>
    <td class="px-1 py-1">
        <input name="<?php echo e($n); ?>[<?php echo e($f); ?>_max]" type="number" step="0.1" class="<?php echo e($inp); ?>" placeholder="max"
               value="<?php echo e($row[$f.'_max'] ?? ''); ?>">
    </td>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php $__currentLoopData = ['product_chest','product_waist','product_length','product_shoulder']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <td class="px-1 py-1">
        <input name="<?php echo e($n); ?>[<?php echo e($f); ?>]" type="number" step="0.1" class="<?php echo e($inp); ?>" placeholder="cm"
               value="<?php echo e($row[$f] ?? ''); ?>">
    </td>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <td class="px-1 py-1">
        <button type="button" class="remove-row text-red-400 hover:text-red-600 text-lg leading-none">×</button>
    </td>
</tr>
<?php /**PATH D:\aven\packages\Webkul\SizeGuide\src\Resources\views\admin\size-guide\partials\row.blade.php ENDPATH**/ ?>