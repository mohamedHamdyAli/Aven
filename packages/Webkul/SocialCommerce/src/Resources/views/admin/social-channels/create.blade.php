<x-admin::layouts>
    <x-slot:title>
        @lang('social-commerce::app.admin.social-channels.create.title')
    </x-slot>

    <x-admin::form :action="route('admin.social-commerce.channels.store')">
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('social-commerce::app.admin.social-channels.create.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <a href="{{ route('admin.social-commerce.channels.index') }}" class="transparent-button">
                    @lang('admin::app.components.form.back-btn')
                </a>

                <button type="submit" class="primary-button">
                    @lang('social-commerce::app.admin.social-channels.create.save-btn')
                </button>
            </div>
        </div>

        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">

                {{-- General --}}
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-600 dark:text-gray-300">
                            @lang('social-commerce::app.admin.social-channels.create.general')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label class="required">
                                @lang('social-commerce::app.admin.social-channels.create.channel')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="select"
                                name="channel_id"
                                :value="old('channel_id')"
                            >
                                <option value="">@lang('admin::app.components.form.select-option')</option>
                                @foreach ($channels as $channel)
                                    <option value="{{ $channel->id }}" @selected(old('channel_id') == $channel->id)>
                                        {{ $channel->name }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>
                            <x-admin::form.control-group.error control-name="channel_id" />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label class="required">
                                @lang('social-commerce::app.admin.social-channels.create.platform')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="select"
                                name="platform"
                                :value="old('platform')"
                            >
                                <option value="">@lang('admin::app.components.form.select-option')</option>
                                <option value="facebook"  @selected(old('platform') === 'facebook')>Facebook</option>
                                <option value="instagram" @selected(old('platform') === 'instagram')>Instagram</option>
                                <option value="tiktok"    @selected(old('platform') === 'tiktok')>TikTok</option>
                                <option value="youtube"   @selected(old('platform') === 'youtube')>YouTube</option>
                                <option value="whatsapp"  @selected(old('platform') === 'whatsapp')>WhatsApp</option>
                            </x-admin::form.control-group.control>
                            <x-admin::form.control-group.error control-name="platform" />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label>
                                @lang('social-commerce::app.admin.social-channels.create.page-url')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="page_url"
                                :value="old('page_url')"
                                placeholder="https://www.facebook.com/yourpage"
                            />
                            <x-admin::form.control-group.error control-name="page_url" />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label>
                                @lang('social-commerce::app.admin.social-channels.create.page-id')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="page_id"
                                :value="old('page_id')"
                            />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label>
                                @lang('social-commerce::app.admin.social-channels.create.status')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="switch"
                                name="is_active"
                                :value="1"
                                :checked="old('is_active')"
                            />
                        </x-admin::form.control-group>
                    </x-slot>
                </x-admin::accordion>

                {{-- Tracking --}}
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-600 dark:text-gray-300">
                            @lang('social-commerce::app.admin.social-channels.create.tracking')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label>
                                @lang('social-commerce::app.admin.social-channels.create.pixel-id')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="pixel_id"
                                :value="old('pixel_id')"
                            />
                        </x-admin::form.control-group>
                    </x-slot>
                </x-admin::accordion>

                {{-- API Credentials --}}
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-600 dark:text-gray-300">
                            @lang('social-commerce::app.admin.social-channels.create.api-credentials')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label>
                                @lang('social-commerce::app.admin.social-channels.create.app-id')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="app_id"
                                :value="old('app_id')"
                            />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label>
                                @lang('social-commerce::app.admin.social-channels.create.app-secret')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="password"
                                name="app_secret"
                            />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label>
                                @lang('social-commerce::app.admin.social-channels.create.access-token')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="textarea"
                                name="access_token"
                            >{{ old('access_token') }}</x-admin::form.control-group.control>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label>
                                @lang('social-commerce::app.admin.social-channels.create.catalog-id')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="catalog_id"
                                :value="old('catalog_id')"
                            />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label>
                                @lang('social-commerce::app.admin.social-channels.create.phone-number-id')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="phone_number_id"
                                :value="old('phone_number_id')"
                                placeholder="WhatsApp Phone Number ID"
                            />
                        </x-admin::form.control-group>
                    </x-slot>
                </x-admin::accordion>

            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>
