<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.marketing.promotions.coupon-assignments.create-title')
    </x-slot>

    <div class="flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.marketing.promotions.coupon-assignments.create-title')
        </p>
    </div>

    <div class="mt-6 max-w-3xl">
        <div class="box-shadow rounded bg-white p-6 dark:bg-gray-900">

            @if ($errors->any())
                <div class="mb-4 rounded bg-red-50 p-3 text-sm text-red-600 dark:bg-red-900/20 dark:text-red-400">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('admin.marketing.promotions.coupon_assignments.store') }}"
                enctype="multipart/form-data"
            >
                @csrf

                {{-- Campaign Name --}}
                <div class="mb-5">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        @lang('admin::app.marketing.promotions.coupon-assignments.campaign-name')
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="campaign_name"
                        value="{{ old('campaign_name') }}"
                        required
                        placeholder="@lang('admin::app.marketing.promotions.coupon-assignments.campaign-placeholder')"
                        class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300"
                    >
                </div>

                {{-- Cart Rule --}}
                <div class="mb-5">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        @lang('admin::app.marketing.promotions.coupon-assignments.cart-rule')
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="cart_rule_id"
                        required
                        class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300"
                    >
                        <option value="">-- @lang('admin::app.marketing.promotions.coupon-assignments.select-rule') --</option>
                        @foreach ($cartRules as $rule)
                            <option value="{{ $rule->id }}" {{ old('cart_rule_id') == $rule->id ? 'selected' : '' }}>
                                {{ $rule->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tabs --}}
                <div class="mt-6">
                    <div class="mb-4 flex gap-3">
                        <button type="button" id="tab-csv-btn"
                            class="rounded-md px-4 py-2 text-sm font-medium transition bg-blue-600 text-white"
                            onclick="switchTab('csv')"
                        >
                            @lang('admin::app.marketing.promotions.coupon-assignments.tab-csv')
                        </button>

                        <button type="button" id="tab-manual-btn"
                            class="rounded-md px-4 py-2 text-sm font-medium transition bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300"
                            onclick="switchTab('manual')"
                        >
                            @lang('admin::app.marketing.promotions.coupon-assignments.tab-manual')
                        </button>
                    </div>

                    <input type="hidden" name="input_type" id="input-type-field" value="csv">

                    {{-- CSV Tab --}}
                    <div id="tab-csv">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            @lang('admin::app.marketing.promotions.coupon-assignments.csv-label')
                        </label>

                        <input
                            type="file"
                            name="csv_file"
                            accept=".csv"
                            class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300"
                        >

                        <p class="mt-1 text-xs text-gray-400">
                            @lang('admin::app.marketing.promotions.coupon-assignments.csv-hint')
                        </p>
                    </div>

                    {{-- Manual Tab --}}
                    <div id="tab-manual" style="display:none">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            @lang('admin::app.marketing.promotions.coupon-assignments.paste-label')
                        </label>

                        <textarea
                            name="phones_raw"
                            id="paste-area"
                            rows="8"
                            placeholder="+201012345678&#10;+201098765432&#10;+201055554444"
                            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 font-mono text-sm text-gray-700 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300"
                        ></textarea>

                        <p class="mt-1 text-xs text-gray-400">
                            @lang('admin::app.marketing.promotions.coupon-assignments.paste-hint')
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('admin.marketing.promotions.coupon_assignments.index') }}" class="secondary-button">
                        @lang('admin::app.marketing.promotions.coupon-assignments.back')
                    </a>

                    <button type="submit" class="primary-button" onclick="return prepareSubmit()">
                        @lang('admin::app.marketing.promotions.coupon-assignments.generate-btn')
                    </button>
                </div>
            </form>
        </div>
    </div>

    @pushOnce('scripts')
    <script>
        function switchTab(tab) {
            document.getElementById('tab-csv').style.display    = tab === 'csv'    ? 'block' : 'none';
            document.getElementById('tab-manual').style.display = tab === 'manual' ? 'block' : 'none';
            document.getElementById('input-type-field').value   = tab;

            document.getElementById('tab-csv-btn').className    = 'rounded-md px-4 py-2 text-sm font-medium transition ' + (tab === 'csv'    ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300');
            document.getElementById('tab-manual-btn').className = 'rounded-md px-4 py-2 text-sm font-medium transition ' + (tab === 'manual' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300');
        }

        function prepareSubmit() {
            const tab = document.getElementById('input-type-field').value;
            if (tab === 'manual') {
                const raw = document.getElementById('paste-area').value.trim();
                if (!raw) { alert('Please enter at least one phone number.'); return false; }
            }
            return true;
        }
    </script>
    @endPushOnce
</x-admin::layouts>
