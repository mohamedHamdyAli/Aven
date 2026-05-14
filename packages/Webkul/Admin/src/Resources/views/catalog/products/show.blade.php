<x-admin::layouts>
    <x-slot:title>
        {{ $product->name }}
    </x-slot>

    <div class="grid gap-2.5">
        <!-- Page Header -->
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <div class="grid gap-1.5">
                <p class="text-xl font-bold leading-6 text-gray-800 dark:text-white">
                    {{ $product->name }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    SKU: {{ $product->sku }}
                    &nbsp;&bull;&nbsp;
                    <span class="capitalize">{{ $product->type }}</span>
                </p>
            </div>

            <div class="flex items-center gap-x-2.5">
                <a
                    href="{{ route('admin.catalog.products.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('admin::app.account.edit.back-btn')
                </a>

                @if (bouncer()->hasPermission('catalog.products.edit'))
                    <a
                        href="{{ route('admin.catalog.products.edit', $product->id) }}"
                        class="primary-button"
                    >
                        @lang('admin::app.catalog.products.edit.title')
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 gap-2.5 lg:grid-cols-3">

            <!-- Left Column: Images + Info -->
            <div class="flex flex-col gap-2.5 lg:col-span-2">

                <!-- Images -->
                @if ($product->images->count())
                    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                        <p class="mb-3 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.catalog.products.edit.title') — Images
                        </p>

                        <div class="flex flex-wrap gap-3">
                            @foreach ($product->images as $image)
                                <img
                                    src="{{ Storage::url($image->path) }}"
                                    class="h-24 w-24 rounded-lg object-cover shadow"
                                />
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Description -->
                @php
                    $productFlat = $product->product_flat->where('locale', app()->getLocale())->first()
                        ?? $product->product_flat->first();
                @endphp

                @if ($productFlat?->short_description)
                    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                        <p class="mb-2 text-base font-semibold text-gray-800 dark:text-white">Short Description</p>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {!! $productFlat->short_description !!}
                        </div>
                    </div>
                @endif

                @if ($productFlat?->description)
                    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                        <p class="mb-2 text-base font-semibold text-gray-800 dark:text-white">Description</p>
                        <div class="prose prose-sm max-w-none text-sm text-gray-600 dark:text-gray-400">
                            {!! $productFlat->description !!}
                        </div>
                    </div>
                @endif

                <!-- Variants (for configurable products) -->
                @if ($product->type === 'configurable' && $product->variants->count())
                    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                        <p class="mb-3 text-base font-semibold text-gray-800 dark:text-white">
                            Variants ({{ $product->variants->count() }})
                        </p>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b text-left text-xs font-medium uppercase text-gray-500 dark:border-gray-700 dark:text-gray-400">
                                        <th class="pb-2 pr-4">Image</th>
                                        <th class="pb-2 pr-4">Name</th>
                                        <th class="pb-2 pr-4">SKU</th>
                                        <th class="pb-2 pr-4">Price</th>
                                        <th class="pb-2 pr-4">Stock</th>
                                        <th class="pb-2"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y dark:divide-gray-700">
                                    @foreach ($product->variants as $variant)
                                        @php
                                            $variantFlat = $variant->product_flat->where('locale', app()->getLocale())->first()
                                                ?? $variant->product_flat->first();
                                            $variantImage = $variant->images->first();
                                            $variantStock = $variant->inventories->sum('qty');
                                        @endphp
                                        <tr class="py-2">
                                            <td class="py-2 pr-4">
                                                @if ($variantImage)
                                                    <img
                                                        src="{{ Storage::url($variantImage->path) }}"
                                                        class="h-12 w-12 rounded object-cover"
                                                    />
                                                @else
                                                    <div class="flex h-12 w-12 items-center justify-center rounded border border-dashed border-gray-300 dark:border-gray-600">
                                                        <span class="text-xs text-gray-400">N/A</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="py-2 pr-4 font-medium text-gray-800 dark:text-white">
                                                {{ $variantFlat?->name ?? $variant->sku }}
                                            </td>
                                            <td class="py-2 pr-4 text-gray-500 dark:text-gray-400">
                                                {{ $variant->sku }}
                                            </td>
                                            <td class="py-2 pr-4 text-gray-700 dark:text-gray-300">
                                                {{ core()->formatPrice($variantFlat?->price ?? 0) }}
                                            </td>
                                            <td class="py-2 pr-4">
                                                @if ($variantStock > 0)
                                                    <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900 dark:text-green-300">
                                                        {{ $variantStock }} Available
                                                    </span>
                                                @else
                                                    <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700 dark:bg-red-900 dark:text-red-300">
                                                        Out of Stock
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-2">
                                                @if (bouncer()->hasPermission('catalog.products.edit'))
                                                    <a
                                                        href="{{ route('admin.catalog.products.edit', $variant->id) }}"
                                                        class="text-blue-600 hover:underline dark:text-blue-400"
                                                    >
                                                        Edit
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Details Card -->
            <div class="flex flex-col gap-2.5">

                <!-- Status & Type -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-3 text-base font-semibold text-gray-800 dark:text-white">Product Details</p>

                    <div class="flex flex-col gap-2 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Status</span>
                            @if ($product->status)
                                <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900 dark:text-green-300">
                                    Active
                                </span>
                            @else
                                <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    Disabled
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Type</span>
                            <span class="font-medium capitalize text-gray-800 dark:text-white">{{ $product->type }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-gray-500 dark:text-gray-400">ID</span>
                            <span class="font-medium text-gray-800 dark:text-white">#{{ $product->id }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-gray-500 dark:text-gray-400">SKU</span>
                            <span class="font-medium text-gray-800 dark:text-white">{{ $product->sku }}</span>
                        </div>

                        @if ($productFlat?->price)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Price</span>
                                <span class="font-semibold text-gray-800 dark:text-white">
                                    {{ core()->formatPrice($productFlat->price) }}
                                </span>
                            </div>
                        @endif

                        @if ($productFlat?->url_key)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 dark:text-gray-400">URL Key</span>
                                <span class="font-medium text-gray-800 dark:text-white">{{ $productFlat->url_key }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Categories -->
                @if ($product->categories->count())
                    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                        <p class="mb-3 text-base font-semibold text-gray-800 dark:text-white">Categories</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($product->categories as $category)
                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                                    {{ $category->translations->where('locale', app()->getLocale())->first()?->name
                                        ?? $category->translations->first()?->name
                                        ?? 'N/A' }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Images count -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-3 text-base font-semibold text-gray-800 dark:text-white">Media</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $product->images->count() }} image(s)
                        @if ($product->type === 'configurable')
                            &nbsp;&bull;&nbsp; {{ $product->variants->count() }} variant(s)
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @once
            @php
                use Illuminate\Support\Facades\Storage;
            @endphp
        @endonce
    @endpush
</x-admin::layouts>
