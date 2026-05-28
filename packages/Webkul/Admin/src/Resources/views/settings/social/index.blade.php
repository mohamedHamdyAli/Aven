<x-admin::layouts>
    <x-slot:title>Social & Integrations</x-slot>

    <div class="flex flex-col gap-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                Social & Integrations
            </p>
            <a
                href="{{ route('admin.configuration.index', ['slug' => 'general', 'slug2' => 'content']) }}"
                class="primary-button"
            >
                Advanced Settings
            </a>
        </div>

        {{-- Groups --}}
        @foreach ($groups as $group)
            <div class="flex flex-col gap-3">

                <p class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    {{ $group['label'] }}
                </p>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($group['integrations'] as $item)
                        <div class="flex flex-col gap-3 rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">

                            {{-- Icon + badge --}}
                            <div class="flex items-center justify-between">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-lg text-white text-lg"
                                    style="background-color: {{ $item['color'] }}"
                                >
                                    <i class="{{ $item['logo'] }}"></i>
                                </div>

                                <div class="flex items-center gap-2">
                                    @if (!empty($item['badge']))
                                        <span class="rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                                            {{ $item['badge'] }}
                                        </span>
                                    @endif

                                    @if ($item['active'])
                                        <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-700 dark:bg-green-900 dark:text-green-300">
                                            Active
                                        </span>
                                    @else
                                        <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                            Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Title + description --}}
                            <div>
                                <p class="font-semibold text-gray-800 dark:text-white">
                                    {{ $item['title'] }}
                                </p>
                                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $item['description'] }}
                                </p>
                            </div>

                            {{-- Action link --}}
                            <a
                                href="{{ $item['config_url'] }}"
                                class="mt-auto inline-flex items-center gap-1 text-sm font-medium text-blue-600 hover:underline dark:text-blue-400"
                            >
                                {{ !empty($item['is_page']) ? 'Manage' : 'Configure' }}
                                <span class="icon-arrow-right text-base"></span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

    </div>
</x-admin::layouts>
