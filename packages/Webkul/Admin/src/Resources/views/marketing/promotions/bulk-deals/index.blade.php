<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.marketing.promotions.bulk-deals.index.title')
    </x-slot>

    <div class="mt-3 flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.marketing.promotions.bulk-deals.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            <a
                href="{{ route('admin.marketing.promotions.bulk_deals.create') }}"
                class="primary-button"
            >
                @lang('admin::app.marketing.promotions.bulk-deals.index.create-btn')
            </a>
        </div>
    </div>

    <x-admin::datagrid :src="route('admin.marketing.promotions.bulk_deals.index')" />

</x-admin::layouts>
