<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title'      => '',
    'isSelected' => false,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'title'      => '',
    'isSelected' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<v-tab-item
    title="<?php echo e($title); ?>"
    is-selected="<?php echo e($isSelected); ?>"
    <?php echo e($attributes->merge(['class' => 'p-5 max-1180:px-5'])); ?>

>
    <template v-slot>
        <?php echo e($slot); ?>

    </template>
</v-tab-item>

<?php if (! $__env->hasRenderedOnce('5a36dec4-03e4-4046-89de-445e42b0698c')): $__env->markAsRenderedOnce('5a36dec4-03e4-4046-89de-445e42b0698c');
$__env->startPush('scripts'); ?>
    <script
        type="text/x-template"
        id="v-tab-item-template"
    >
        <div
            v-if="isActive"
            class="animate-[on-fade_0.5s_ease-in-out]"
        >
            <slot></slot>
        </div>
    </script>

    <script type="module">
        app.component('v-tab-item', {
            template: '#v-tab-item-template',

            props: ['title', 'isSelected'],

            data() {
                return {
                    isActive: false,
                    tabId: '',
                }
            },

            mounted() {
                this.tabId = this.$attrs?.id ?? '';
                this.isActive = this.isSelected;

                /**
                 * On mounted, pushing element to its parents component.
                 */
                this.$parent.$data.tabs.push(this);
            }
        });
    </script>
<?php $__env->stopPush(); endif; ?>
<?php /**PATH D:\aven\packages\Webkul\Shop\src/resources/views/components/tabs/item.blade.php ENDPATH**/ ?>