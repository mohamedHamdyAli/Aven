<x-admin::layouts>
    <x-slot:title>Payment Methods</x-slot>

    <div class="flex flex-col gap-4">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                Payment Methods
            </p>
            <a
                href="{{ $configUrl }}"
                class="primary-button"
            >
                Advanced Settings
            </a>
        </div>

        {{-- Cards Grid --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($methods as $method)
                <div class="flex flex-col gap-3 rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">

                    <div class="flex items-center justify-between">
                        <span class="{{ $method['icon'] }} text-3xl text-gray-500 dark:text-gray-400"></span>

                        @if ($method['active'])
                            <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-700 dark:bg-green-900 dark:text-green-300">
                                Active
                            </span>
                        @else
                            <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                Inactive
                            </span>
                        @endif
                    </div>

                    <div>
                        <p class="font-semibold text-gray-800 dark:text-white">
                            {{ $method['title'] }}
                        </p>
                        <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                            {{ $method['description'] }}
                        </p>
                    </div>

                    <a
                        href="{{ $configUrl }}#{{ $method['key'] }}"
                        class="mt-auto inline-flex items-center gap-1 text-sm font-medium text-blue-600 hover:underline dark:text-blue-400"
                    >
                        Configure
                        <span class="icon-arrow-right text-base"></span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</x-admin::layouts>
