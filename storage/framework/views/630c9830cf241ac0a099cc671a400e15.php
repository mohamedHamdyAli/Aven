<?php if($product->type == 'booking'): ?>

    <?php
        $bookingProduct = $product->booking_products()->first();
    ?>
    
    <?php echo view_render_event('bagisto.shop.products.view.booking.before', ['product' => $product]); ?>


    <v-booking-information></v-booking-information>

    <?php echo view_render_event('bagisto.shop.products.view.booking.before', ['product' => $product]); ?>


    <?php if (! $__env->hasRenderedOnce('bf5d2f14-d521-4e3c-a38a-01048b4ab7f8')): $__env->markAsRenderedOnce('bf5d2f14-d521-4e3c-a38a-01048b4ab7f8');
$__env->startPush('scripts'); ?>
        <script
            type="text/x-template"
            id="v-booking-information-template"
        >
            <div class="mt-6 grid w-full max-w-[470px] grid-cols-1 gap-6">
                <?php if($bookingProduct->location): ?>
                    <div class="flex gap-4">
                        <span class="icon-location text-2xl"></span>

                        <div class="grid grid-cols-1 gap-1.5 text-sm font-medium">
                            <p>
                                <?php echo app('translator')->get('shop::app.products.view.type.booking.location'); ?>
                            </p>

                            <div class="grid grid-cols-1 gap-3">
                                <p
                                    class="text-[#6E6E6E]"
                                    v-pre
                                >
                                    <?php echo e($bookingProduct->location); ?>

                                </p>

                                <a
                                    href="https://maps.google.com/maps?q=<?php echo e($bookingProduct->location); ?>"
                                    target="_blank"
                                    class="w-fit text-blue-600 hover:text-blue-800"
                                >
                                    <?php echo app('translator')->get('shop::app.products.view.type.booking.view-on-map'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="w-full max-w-[470px]">
                    <?php echo $__env->make('shop::products.view.types.booking.' . $bookingProduct->type, ['bookingProduct' => $bookingProduct], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>

                <?php if(! $bookingProduct->allow_cancellation): ?>
                    <div class="flex items-start gap-3 rounded-md border border-amber-300 bg-amber-50 p-3 text-sm text-amber-900">
                        <span class="icon-warning mt-0.5 text-lg"></span>

                        <div>
                            <p class="font-semibold">
                                <?php echo app('translator')->get('shop::app.products.view.type.booking.cancellation-not-allowed.title'); ?>
                            </p>

                            <p class="text-xs">
                                <?php echo app('translator')->get('shop::app.products.view.type.booking.cancellation-not-allowed.description'); ?>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </script>

        <script type="module">
            app.component('v-booking-information', {
                template: '#v-booking-information-template',

            });
        </script>
    <?php $__env->stopPush(); endif; ?>
<?php endif; ?>
<?php /**PATH D:\aven\packages\Webkul\Shop\src/resources/views/products/view/types/booking.blade.php ENDPATH**/ ?>