@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-checkout-address-form-template"
    >
        <div class="mt-2 max-md:mt-3">
            <x-shop::form.control-group class="hidden">
                <x-shop::form.control-group.control
                    type="text"
                    ::name="controlName + '.id'"
                    ::value="address.id"
                />
            </x-shop::form.control-group>

            <!-- First Name -->
            <div class="grid grid-cols-2 gap-x-5 max-md:grid-cols-1">
                <x-shop::form.control-group>
                    <x-shop::form.control-group.label class="required !mt-0">
                        @lang('shop::app.checkout.onepage.address.first-name')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="text"
                        ::name="controlName + '.first_name'"
                        ::value="address.first_name"
                        rules="required"
                        :label="trans('shop::app.checkout.onepage.address.first-name')"
                        :placeholder="trans('shop::app.checkout.onepage.address.first-name')"
                    />

                    <x-shop::form.control-group.error ::name="controlName + '.first_name'" />
                </x-shop::form.control-group>

                {!! view_render_event('bagisto.shop.checkout.onepage.address.form.first_name.after') !!}

                <!-- Last Name -->
                <x-shop::form.control-group>
                    <x-shop::form.control-group.label class="required !mt-0">
                        @lang('shop::app.checkout.onepage.address.last-name')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="text"
                        ::name="controlName + '.last_name'"
                        ::value="address.last_name"
                        rules="required"
                        :label="trans('shop::app.checkout.onepage.address.last-name')"
                        :placeholder="trans('shop::app.checkout.onepage.address.last-name')"
                    />

                    <x-shop::form.control-group.error ::name="controlName + '.last_name'" />
                </x-shop::form.control-group>

                {!! view_render_event('bagisto.shop.checkout.onepage.address.form.last_name.after') !!}
            </div>

            <!-- Email -->
            <x-shop::form.control-group>
                <x-shop::form.control-group.label class="!mt-0">
                    @lang('shop::app.checkout.onepage.address.email')
                </x-shop::form.control-group.label>

                <x-shop::form.control-group.control
                    type="email"
                    ::name="controlName + '.email'"
                    ::value="address.email"
                    rules="email"
                    :label="trans('shop::app.checkout.onepage.address.email')"
                    placeholder="email@example.com"
                />

                <x-shop::form.control-group.error ::name="controlName + '.email'" />
            </x-shop::form.control-group>

            {!! view_render_event('bagisto.shop.checkout.onepage.address.form.email.after') !!}

            <!-- Street Address -->
            <x-shop::form.control-group>
                <x-shop::form.control-group.label class="required !mt-0">
                    @lang('shop::app.checkout.onepage.address.street-address')
                </x-shop::form.control-group.label>

                <x-shop::form.control-group.control
                    type="text"
                    ::name="controlName + '.address.[0]'"
                    ::value="address.address[0]"
                    rules="required|address"
                    :label="trans('shop::app.checkout.onepage.address.street-address')"
                    :placeholder="trans('shop::app.checkout.onepage.address.street-address')"
                />

                <x-shop::form.control-group.error
                    class="mb-2"
                    ::name="controlName + '.address.[0]'"
                />

                @if (core()->getConfigData('customer.address.information.street_lines') > 1)
                    @for ($i = 1; $i < core()->getConfigData('customer.address.information.street_lines'); $i++)
                        <x-shop::form.control-group.control
                            type="text"
                            ::name="controlName + '.address.[{{ $i }}]'"
                            rules="address"
                            :label="trans('shop::app.checkout.onepage.address.street-address')"
                            :placeholder="trans('shop::app.checkout.onepage.address.street-address')"
                        />

                        <x-shop::form.control-group.error
                            class="mb-2"
                            ::name="controlName + '.address.[{{ $i }}]'"
                        />
                    @endfor
                @endif
            </x-shop::form.control-group>

            {!! view_render_event('bagisto.shop.checkout.onepage.address.form.address.after') !!}

            <!-- Country -->
            <x-shop::form.control-group class="!mb-4">
                <x-shop::form.control-group.label class="!mt-0">
                    @lang('shop::app.checkout.onepage.address.country')
                </x-shop::form.control-group.label>

                <x-shop::form.control-group.control
                    type="select"
                    ::name="controlName + '.country'"
                    ::value="address.country"
                    v-model="selectedCountry"
                    rules=""
                    :label="trans('shop::app.checkout.onepage.address.country')"
                    :placeholder="trans('shop::app.checkout.onepage.address.country')"
                >
                    <option value="">
                        @lang('shop::app.checkout.onepage.address.select-country')
                    </option>

                    <option
                        v-for="country in countries"
                        :value="country.code"
                    >
                        @{{ country.name }}
                    </option>
                </x-shop::form.control-group.control>

                <x-shop::form.control-group.error ::name="controlName + '.country'" />
            </x-shop::form.control-group>

            {!! view_render_event('bagisto.shop.checkout.onepage.address.form.country.after') !!}

            <!-- City / Governorate -->
            @php
                $egyptGovernorates = \Webkul\EgyptShipping\Models\EgyptGovernorate::where('is_active', true)
                    ->orderBy('name_en')
                    ->get(['code', 'name_en', 'name_ar']);
            @endphp

            <x-shop::form.control-group>
                <x-shop::form.control-group.label class="required !mt-0">
                    @lang('shop::app.checkout.onepage.address.city')
                </x-shop::form.control-group.label>

                {{-- Hidden fields registered with VeeValidate so they're included in form params --}}
                <x-shop::form.control-group class="hidden">
                    <x-shop::form.control-group.control
                        type="hidden"
                        ::name="controlName + '.city'"
                        ::value="address.city"
                    />
                </x-shop::form.control-group>

                <x-shop::form.control-group class="hidden">
                    <x-shop::form.control-group.control
                        type="hidden"
                        ::name="controlName + '.state'"
                        ::value="address.state"
                    />
                </x-shop::form.control-group>

                <select
                    class="custom-select w-full rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-navyBlue focus:outline-none"
                    @change="onGovernorateChange($event)"
                    v-model="selectedGovernorate"
                >
                    <option value="">— Select Governorate —</option>
                    @foreach($egyptGovernorates as $gov)
                        <option value="{{ $gov->code }}">{{ $gov->name_en }} — {{ $gov->name_ar }}</option>
                    @endforeach
                </select>

                <p v-if="!selectedGovernorate && governorateError" class="mt-1 text-xs text-red-600">
                    Please select a governorate.
                </p>
            </x-shop::form.control-group>

            {!! view_render_event('bagisto.shop.checkout.onepage.address.form.city.after') !!}

            <!-- Phone Number -->
            <x-shop::form.control-group>
                <x-shop::form.control-group.label class="required !mt-0">
                    @lang('shop::app.checkout.onepage.address.telephone')
                </x-shop::form.control-group.label>

                <x-shop::form.control-group.control
                    type="text"
                    ::name="controlName + '.phone'"
                    ::value="address.phone"
                    rules="required|phone"
                    :label="trans('shop::app.checkout.onepage.address.telephone')"
                    :placeholder="trans('shop::app.checkout.onepage.address.telephone')"
                />

                <x-shop::form.control-group.error ::name="controlName + '.phone'" />
            </x-shop::form.control-group>

            {!! view_render_event('bagisto.shop.checkout.onepage.address.form.phone.after') !!}
        </div>
    </script>

    <script type="module">
        app.component('v-checkout-address-form', {
            template: '#v-checkout-address-form-template',

            props: {
                controlName: {
                    type: String,
                    required: true,
                },

                address: {
                    type: Object,

                    default: () => ({
                        id: 0,
                        company_name: '',
                        first_name: '',
                        last_name: '',
                        email: '',
                        address: [],
                        country: '',
                        state: '',
                        city: '',
                        postcode: '',
                        phone: '',
                    }),
                },
            },

            data() {
                /* pre-select: use state if set, else fall back to city (old addresses stored city without state) */
                const initCode = this.address.state || this.address.city || '';

                return {
                    selectedCountry:    this.address.country,
                    selectedGovernorate: initCode,
                    governorateError:   false,
                    countries: [],
                }
            },

            mounted() {
                this.getCountries();
                /* sync address fields from initial selection */
                this.syncGovernorate(this.selectedGovernorate);
            },

            watch: {
                selectedGovernorate(code) {
                    this.syncGovernorate(code);
                },
            },

            methods: {
                getCountries() {
                    this.$axios.get("{{ route('shop.api.core.countries') }}")
                        .then(response => {
                            this.countries = response.data.data;
                        })
                        .catch(() => {});
                },

                syncGovernorate(code) {
                    if (!code) {
                        this.address.state = '';
                        this.address.city  = '';
                        this.$emitter.emit('egypt-shipping-rate', { rate: null, formatted: null });
                        return;
                    }
                    const govs = @json($egyptGovernorates->keyBy('code'));
                    const gov  = govs[code];
                    this.address.state = code;
                    this.address.city  = gov ? gov.name_en : code;

                    this.$axios.get(`/api/egypt-shipping/rate/${code}`)
                        .then(r => {
                            this.$emitter.emit('egypt-shipping-rate', {
                                rate:      r.data.rate,
                                formatted: r.data.formatted,
                            });
                        })
                        .catch(() => {
                            this.$emitter.emit('egypt-shipping-rate', { rate: null, formatted: null });
                        });
                },

                onGovernorateChange(event) {
                    this.governorateError = !event.target.value;
                },
            }
        });
    </script>
@endPushOnce
