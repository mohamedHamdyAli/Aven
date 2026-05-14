<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.marketing.promotions.bulk-deals.edit.title')
    </x-slot>

    <div class="mt-3 flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.marketing.promotions.bulk-deals.edit.title')
        </p>
    </div>

    <form
        method="POST"
        action="{{ route('admin.marketing.promotions.bulk_deals.update', $bulkDeal->id) }}"
    >
        @csrf
        @method('PUT')

        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            {{-- Left column --}}
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">

                {{-- General --}}
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.marketing.promotions.bulk-deals.create.general')
                    </p>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.marketing.promotions.bulk-deals.create.name')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="name"
                            :value="old('name', $bulkDeal->name)"
                            rules="required"
                            :label="trans('admin::app.marketing.promotions.bulk-deals.create.name')"
                        />

                        <x-admin::form.control-group.error control-name="name" />
                    </x-admin::form.control-group>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('admin::app.marketing.promotions.bulk-deals.create.description')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="description"
                            :value="old('description', $bulkDeal->description)"
                        />
                    </x-admin::form.control-group>
                </div>

                {{-- Deal Settings --}}
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.marketing.promotions.bulk-deals.create.deal-settings')
                    </p>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.marketing.promotions.bulk-deals.create.paid-quantity')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="number"
                            name="paid_quantity"
                            :value="old('paid_quantity', $bulkDeal->paid_quantity)"
                            rules="required|min_value:0"
                            :label="trans('admin::app.marketing.promotions.bulk-deals.create.paid-quantity')"
                        />

                        <x-admin::form.control-group.error control-name="paid_quantity" />
                    </x-admin::form.control-group>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.marketing.promotions.bulk-deals.create.deal-quantity')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="number"
                            name="deal_quantity"
                            :value="old('deal_quantity', $bulkDeal->deal_quantity)"
                            rules="required|min_value:1"
                            :label="trans('admin::app.marketing.promotions.bulk-deals.create.deal-quantity')"
                        />

                        <x-admin::form.control-group.error control-name="deal_quantity" />
                    </x-admin::form.control-group>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.marketing.promotions.bulk-deals.create.deal-price')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="number"
                            name="deal_price"
                            step="0.01"
                            :value="old('deal_price', $bulkDeal->deal_price)"
                            rules="required|min_value:0"
                            :label="trans('admin::app.marketing.promotions.bulk-deals.create.deal-price')"
                        />

                        <x-admin::form.control-group.error control-name="deal_price" />
                    </x-admin::form.control-group>
                </div>

            </div>

            {{-- Right column --}}
            <div class="flex w-[360px] flex-col gap-2 max-xl:w-full">

                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.marketing.promotions.bulk-deals.create.settings')
                    </p>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.marketing.promotions.bulk-deals.create.status')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="status"
                            :value="old('status', $bulkDeal->status ? 1 : 0)"
                        >
                            <option value="1" @selected($bulkDeal->status)>@lang('admin::app.marketing.promotions.bulk-deals.create.active')</option>
                            <option value="0" @selected(! $bulkDeal->status)>@lang('admin::app.marketing.promotions.bulk-deals.create.inactive')</option>
                        </x-admin::form.control-group.control>
                    </x-admin::form.control-group>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('admin::app.marketing.promotions.bulk-deals.create.starts-from')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="date"
                            name="starts_from"
                            :value="old('starts_from', $bulkDeal->starts_from?->format('Y-m-d'))"
                        />
                    </x-admin::form.control-group>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('admin::app.marketing.promotions.bulk-deals.create.ends-till')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="date"
                            name="ends_till"
                            :value="old('ends_till', $bulkDeal->ends_till?->format('Y-m-d'))"
                        />
                    </x-admin::form.control-group>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('admin::app.marketing.promotions.bulk-deals.create.sort-order')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="number"
                            name="sort_order"
                            :value="old('sort_order', $bulkDeal->sort_order)"
                        />
                    </x-admin::form.control-group>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="primary-button">
                        @lang('admin::app.marketing.promotions.bulk-deals.edit.save-btn')
                    </button>
                </div>
            </div>
        </div>
    </form>

</x-admin::layouts>
