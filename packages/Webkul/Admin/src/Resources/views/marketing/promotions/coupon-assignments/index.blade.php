<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.marketing.promotions.coupon-assignments.title')
    </x-slot>

    <div class="flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.marketing.promotions.coupon-assignments.title')
        </p>

        <a
            href="{{ route('admin.marketing.promotions.coupon_assignments.create') }}"
            class="primary-button"
        >
            @lang('admin::app.marketing.promotions.coupon-assignments.create-btn')
        </a>
    </div>

    <x-admin::datagrid :src="route('admin.marketing.promotions.coupon_assignments.index')" />
</x-admin::layouts>
