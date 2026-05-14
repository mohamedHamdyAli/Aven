<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.store-locator.index.title')
    </x-slot>

    <div class="flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.store-locator.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            <a
                href="{{ route('admin.store-locator.create') }}"
                class="primary-button"
            >
                @lang('admin::app.store-locator.index.create-btn')
            </a>
        </div>
    </div>

    <x-admin::datagrid :src="route('admin.store-locator.index')" />

</x-admin::layouts>
