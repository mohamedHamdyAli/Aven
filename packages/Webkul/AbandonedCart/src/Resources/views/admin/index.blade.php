<x-admin::layouts>
    <x-slot:title>
        {{ trans('abandoned-cart::app.admin.title') }}
    </x-slot>

    <div class="flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            {{ trans('abandoned-cart::app.admin.title') }}
        </p>
    </div>

    <x-admin::datagrid :src="route('admin.sales.abandoned-carts.index')" />
</x-admin::layouts>
