<x-admin::layouts>
    <x-slot:title>Product Costs</x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800">
            Product Costs &amp; Margins
        </p>
    </div>

    <div class="mt-4 rounded-lg border border-gray-200 bg-white p-4 text-sm text-gray-600">
        Enter the cost of each product to calculate profit margins. Products without costs entered show 0% margin.
    </div>

    <x-admin::datagrid :src="route('admin.cost_management.products.index')" />
</x-admin::layouts>
