<x-shop::layouts>
    <x-slot:title>
        @lang('egypt-shipping::app.track-order.title')
    </x-slot>

    <div class="container mx-auto mt-10 mb-16 px-4 max-w-lg">
        <div class="rounded-xl border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <h1 class="mb-2 text-center text-2xl font-bold text-gray-800 dark:text-white">
                @lang('egypt-shipping::app.track-order.title')
            </h1>
            <p class="mb-6 text-center text-sm text-gray-500 dark:text-gray-400">
                @lang('egypt-shipping::app.track-order.subtitle')
            </p>

            @if (session('error'))
                <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700 dark:bg-red-900/20 dark:border-red-800 dark:text-red-400">
                    {{ session('error') }}
                </div>
            @endif

            <form
                action="{{ route('egypt-shipping.track-order.show') }}"
                method="POST"
                class="space-y-4"
            >
                @csrf

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        @lang('egypt-shipping::app.track-order.order-id')
                    </label>
                    <input
                        type="text"
                        name="increment_id"
                        value="{{ old('increment_id') }}"
                        placeholder="000000001"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white @error('increment_id') border-red-500 @enderror"
                        required
                    >
                    @error('increment_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        @lang('egypt-shipping::app.track-order.email')
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="example@email.com"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white @error('email') border-red-500 @enderror"
                        required
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full rounded-lg bg-blue-600 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition"
                >
                    @lang('egypt-shipping::app.track-order.track-btn')
                </button>
            </form>
        </div>
    </div>
</x-shop::layouts>
