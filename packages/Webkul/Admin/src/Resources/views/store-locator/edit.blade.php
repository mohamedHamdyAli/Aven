@php
    $days = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
    $googleMapsApiKey = core()->getConfigData('store_locator.settings.google_maps_api_key') ?? '';
    $workingHours = $branch->working_hours ?? [];
@endphp

<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.store-locator.edit.title')
    </x-slot>

    <x-admin::form
        :action="route('admin.store-locator.update', $branch->id)"
        method="PUT"
        enctype="multipart/form-data"
    >
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.store-locator.edit.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <a
                    href="{{ route('admin.store-locator.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('admin::app.store-locator.edit.back-btn')
                </a>

                <button type="submit" class="primary-button">
                    @lang('admin::app.store-locator.edit.save-btn')
                </button>
            </div>
        </div>

        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left column -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">

                <!-- Basic Info -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.store-locator.edit.basic-info')
                    </p>

                    <!-- Name -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.store-locator.edit.name')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="name"
                            rules="required"
                            :value="old('name', $branch->name)"
                            :label="trans('admin::app.store-locator.edit.name')"
                            :placeholder="trans('admin::app.store-locator.edit.name')"
                        />

                        <x-admin::form.control-group.error control-name="name" />
                    </x-admin::form.control-group>

                    <!-- Phone -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('admin::app.store-locator.edit.phone')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="phone"
                            :value="old('phone', $branch->phone)"
                            :label="trans('admin::app.store-locator.edit.phone')"
                            :placeholder="trans('admin::app.store-locator.edit.phone')"
                        />

                        <x-admin::form.control-group.error control-name="phone" />
                    </x-admin::form.control-group>

                    <!-- Address -->
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.store-locator.edit.address')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            id="address-input"
                            name="address"
                            rules="required"
                            :value="old('address', $branch->address)"
                            :label="trans('admin::app.store-locator.edit.address')"
                            :placeholder="trans('admin::app.store-locator.edit.address')"
                        />

                        <x-admin::form.control-group.error control-name="address" />
                    </x-admin::form.control-group>
                </div>

                <!-- Google Maps Picker -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-3 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.store-locator.edit.map-title')
                    </p>

                    <p class="mb-3 text-sm text-gray-500 dark:text-gray-400">
                        @lang('admin::app.store-locator.edit.map-hint')
                    </p>

                    <input type="hidden" id="latitude-input" name="latitude" value="{{ old('latitude', $branch->latitude) }}" />
                    <input type="hidden" id="longitude-input" name="longitude" value="{{ old('longitude', $branch->longitude) }}" />

                    @if ($googleMapsApiKey)
                        <div id="store-map" style="width:100%; height:420px; border-radius:8px;"></div>
                    @else
                        <div class="rounded-lg border border-amber-300 bg-amber-50 p-4 text-amber-800 dark:bg-amber-900/20 dark:text-amber-300">
                            @lang('admin::app.store-locator.edit.no-api-key')
                            <a href="{{ route('admin.configuration.index', 'store_locator/settings') }}" class="underline">
                                @lang('admin::app.store-locator.edit.configure-api-key')
                            </a>
                        </div>

                        <div class="mt-3 grid grid-cols-2 gap-4">
                            <x-admin::form.control-group class="!mb-0">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.store-locator.edit.latitude')
                                </x-admin::form.control-group.label>
                                <input
                                    type="number"
                                    step="any"
                                    name="latitude"
                                    id="latitude-manual"
                                    value="{{ old('latitude', $branch->latitude) }}"
                                    class="w-full rounded-md border px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                />
                                <x-admin::form.control-group.error control-name="latitude" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group class="!mb-0">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.store-locator.edit.longitude')
                                </x-admin::form.control-group.label>
                                <input
                                    type="number"
                                    step="any"
                                    name="longitude"
                                    id="longitude-manual"
                                    value="{{ old('longitude', $branch->longitude) }}"
                                    class="w-full rounded-md border px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                />
                                <x-admin::form.control-group.error control-name="longitude" />
                            </x-admin::form.control-group>
                        </div>
                    @endif
                </div>

                <!-- Working Hours -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.store-locator.edit.working-hours')
                    </p>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b text-left text-gray-500 dark:border-gray-700">
                                    <th class="pb-2 font-medium">@lang('admin::app.store-locator.create.day')</th>
                                    <th class="pb-2 font-medium">@lang('admin::app.store-locator.create.open')</th>
                                    <th class="pb-2 font-medium">@lang('admin::app.store-locator.create.from')</th>
                                    <th class="pb-2 font-medium">@lang('admin::app.store-locator.create.to')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($days as $day)
                                    @php
                                        $dayData = old('working_hours.'.$day, $workingHours[$day] ?? []);
                                        $isOpen = isset($dayData['open']) ? (bool) $dayData['open'] : ($day !== 'friday');
                                        $from = $dayData['from'] ?? '09:00';
                                        $to = $dayData['to'] ?? '22:00';
                                    @endphp
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="py-2 pr-4 font-medium capitalize text-gray-700 dark:text-gray-300">
                                            @lang('admin::app.store-locator.days.'.$day)
                                        </td>
                                        <td class="py-2 pr-4">
                                            <label class="relative inline-flex cursor-pointer items-center">
                                                <input
                                                    type="checkbox"
                                                    name="working_hours[{{ $day }}][open]"
                                                    value="1"
                                                    class="working-hours-toggle peer sr-only"
                                                    data-day="{{ $day }}"
                                                    {{ $isOpen ? 'checked' : '' }}
                                                />
                                                <div class="peer h-5 w-9 rounded-full bg-gray-300 after:absolute after:start-[2px] after:top-[2px] after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all peer-checked:bg-blue-600 peer-checked:after:translate-x-full dark:bg-gray-600"></div>
                                            </label>
                                        </td>
                                        <td class="py-2 pr-4">
                                            <input
                                                type="time"
                                                name="working_hours[{{ $day }}][from]"
                                                value="{{ $from }}"
                                                class="hours-field-{{ $day }} rounded border px-2 py-1 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                                {{ ! $isOpen ? 'disabled' : '' }}
                                            />
                                        </td>
                                        <td class="py-2">
                                            <input
                                                type="time"
                                                name="working_hours[{{ $day }}][to]"
                                                value="{{ $to }}"
                                                class="hours-field-{{ $day }} rounded border px-2 py-1 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                                {{ ! $isOpen ? 'disabled' : '' }}
                                            />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Right column -->
            <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">

                <!-- Status -->
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.store-locator.edit.status')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <x-admin::form.control-group class="!mb-0 flex items-center gap-4">
                            <x-admin::form.control-group.control
                                type="switch"
                                name="status"
                                value="1"
                                :label="trans('admin::app.store-locator.edit.status')"
                                :checked="old('status', $branch->status)"
                            />
                            <x-admin::form.control-group.label class="!mb-0">
                                @lang('admin::app.store-locator.edit.active')
                            </x-admin::form.control-group.label>
                        </x-admin::form.control-group>
                    </x-slot>
                </x-admin::accordion>

                <!-- Image -->
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.store-locator.edit.image')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        @if ($branch->image)
                            <div class="mb-3">
                                <img
                                    src="{{ Storage::url($branch->image) }}"
                                    alt="{{ $branch->name }}"
                                    class="h-32 w-full rounded-lg object-cover"
                                />
                            </div>
                        @endif

                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.control
                                type="image"
                                name="image"
                                :label="trans('admin::app.store-locator.edit.image')"
                            />
                            <x-admin::form.control-group.error control-name="image" />
                        </x-admin::form.control-group>
                    </x-slot>
                </x-admin::accordion>

            </div>
        </div>

    </x-admin::form>

    @if ($googleMapsApiKey)
        @pushOnce('scripts')
            <script>
                (function () {
                    const existingLat = {{ $branch->latitude ?? 26.8206 }};
                    const existingLng = {{ $branch->longitude ?? 30.8025 }};
                    let map, marker;

                    window.initStoreMap = function () {
                        map = new google.maps.Map(document.getElementById('store-map'), {
                            center: { lat: existingLat, lng: existingLng },
                            zoom: existingLat !== 26.8206 ? 15 : 6,
                        });

                        marker = new google.maps.Marker({
                            map,
                            position: { lat: existingLat, lng: existingLng },
                            draggable: true,
                        });

                        map.addListener('click', function (e) {
                            placeMarker(e.latLng);
                        });

                        marker.addListener('dragend', function (e) {
                            reverseGeocode(e.latLng);
                        });

                        const input = document.getElementById('address-input');
                        const autocomplete = new google.maps.places.Autocomplete(input);
                        autocomplete.bindTo('bounds', map);

                        autocomplete.addListener('place_changed', function () {
                            const place = autocomplete.getPlace();
                            if (!place.geometry) return;
                            map.setCenter(place.geometry.location);
                            map.setZoom(15);
                            placeMarker(place.geometry.location, false);
                        });
                    };

                    function placeMarker(latLng, geocode = true) {
                        marker.setPosition(latLng);
                        document.getElementById('latitude-input').value = latLng.lat().toFixed(7);
                        document.getElementById('longitude-input').value = latLng.lng().toFixed(7);
                        if (geocode) reverseGeocode(latLng);
                    }

                    function reverseGeocode(latLng) {
                        const geocoder = new google.maps.Geocoder();
                        geocoder.geocode({ location: latLng }, function (results, status) {
                            if (status === 'OK' && results[0]) {
                                document.getElementById('address-input').value = results[0].formatted_address;
                            }
                        });
                    }
                })();
            </script>

            <script
                src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&libraries=places&callback=initStoreMap"
                async
                defer
            ></script>

            <script>
                document.querySelectorAll('.working-hours-toggle').forEach(function (toggle) {
                    toggle.addEventListener('change', function () {
                        const day = this.dataset.day;
                        document.querySelectorAll('.hours-field-' + day).forEach(function (field) {
                            field.disabled = !toggle.checked;
                        });
                    });
                });
            </script>
        @endPushOnce
    @else
        @pushOnce('scripts')
            <script>
                document.querySelectorAll('.working-hours-toggle').forEach(function (toggle) {
                    toggle.addEventListener('change', function () {
                        const day = this.dataset.day;
                        document.querySelectorAll('.hours-field-' + day).forEach(function (field) {
                            field.disabled = !toggle.checked;
                        });
                    });
                });
            </script>
        @endPushOnce
    @endif

</x-admin::layouts>
