<x-admin::layouts>
    <x-slot:title>
        @lang('social-commerce::app.admin.social-channels.index.title')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('social-commerce::app.admin.social-channels.index.title')
        </p>

        @if (bouncer()->hasPermission('social-commerce.channels.create'))
            <a
                href="{{ route('admin.social-commerce.channels.create') }}"
                class="primary-button"
            >
                @lang('social-commerce::app.admin.social-channels.index.create-btn')
            </a>
        @endif
    </div>

    <x-admin::datagrid :src="route('admin.social-commerce.channels.index')" />
</x-admin::layouts>
