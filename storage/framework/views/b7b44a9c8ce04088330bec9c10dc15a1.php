

<div id="sg-trigger-wrap" style="display:none" class="mt-2">
    <a href="<?php echo e(route('shop.size-guide.page', $product->id)); ?>"
       class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-black focus:outline-none">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round">
            <path d="M2 20h20M5 4h14a1 1 0 0 1 1 1v11H4V5a1 1 0 0 1 1-1z"/>
            <line x1="9" y1="8" x2="15" y2="8"/><line x1="9" y1="12" x2="15" y2="12"/>
        </svg>
        <?php echo app('translator')->get('size-guide::app.shop.size-guide.title'); ?>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="9 18 15 12 9 6"/>
        </svg>
    </a>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    var PID = <?php echo e($product->id); ?>;
    function initTrigger() {
        fetch('/size-guide/product/' + PID, { headers: { Accept: 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                if (d.found) {
                    var w = document.getElementById('sg-trigger-wrap');
                    if (w) w.style.removeProperty('display');
                }
            })
            .catch(function () {});
    }
    if (document.readyState === 'complete') { initTrigger(); }
    else { window.addEventListener('load', initTrigger); }
})();
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH D:\aven\packages\Webkul\SizeGuide\src/Resources/views/shop/size-guide-modal.blade.php ENDPATH**/ ?>