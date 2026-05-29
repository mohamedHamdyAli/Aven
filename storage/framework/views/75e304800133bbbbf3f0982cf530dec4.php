<p class="price-label text-sm text-zinc-500 max-sm:text-xs max-sm:leading-4">
    <?php echo app('translator')->get('shop::app.products.prices.configurable.as-low-as'); ?>
</p>

<?php if(isset($prices['final']) && $prices['final']['price'] < $prices['regular']['price']): ?>
    <p class="regular-price text-lg font-semibold text-gray-500 line-through max-sm:text-sm max-sm:leading-4">
        <?php echo e($prices['regular']['formatted_price']); ?>

    </p>

    <p class="final-price font-semibold max-sm:leading-4">
        <?php echo e($prices['final']['formatted_price']); ?>

    </p>
<?php else: ?>
    <p class="regular-price text-lg font-semibold text-gray-500 line-through max-sm:text-sm max-sm:leading-4" style="display: none;"></p>

    <p class="final-price font-semibold max-sm:leading-4">
        <?php echo e($prices['regular']['formatted_price']); ?>

    </p>
<?php endif; ?><?php /**PATH D:\aven\packages\Webkul\Shop\src/resources/views/products/prices/configurable.blade.php ENDPATH**/ ?>