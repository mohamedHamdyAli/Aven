<?php

namespace Webkul\SocialCommerce\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\SocialCommerce\DataGrids\SocialChannelDataGrid;
use Webkul\SocialCommerce\Jobs\SyncProductsToPlatform;
use Webkul\SocialCommerce\Repositories\SocialChannelPlatformRepository;

class SocialChannelController extends Controller
{
    public function __construct(
        protected SocialChannelPlatformRepository $socialChannelPlatformRepository,
        protected ChannelRepository $channelRepository,
    ) {}

    public function index(): mixed
    {
        if (request()->ajax()) {
            return app(SocialChannelDataGrid::class)->toJson();
        }

        return view('social-commerce::admin.social-channels.index');
    }

    public function create(): View
    {
        $channels = $this->channelRepository->all();

        return view('social-commerce::admin.social-channels.create', compact('channels'));
    }

    public function store(): RedirectResponse
    {
        $data = request()->validate([
            'channel_id'    => 'required|integer|exists:channels,id',
            'platform'      => 'required|in:facebook,instagram,tiktok,youtube,whatsapp',
            'is_active'     => 'boolean',
            'page_url'      => 'nullable|url',
            'page_id'       => 'nullable|string|max:255',
            'pixel_id'      => 'nullable|string|max:255',
            'app_id'        => 'nullable|string',
            'app_secret'    => 'nullable|string',
            'access_token'  => 'nullable|string',
            'catalog_id'    => 'nullable|string|max:255',
            'phone_number_id' => 'nullable|string|max:255',
        ]);

        $data['is_active'] = isset($data['is_active']) ? (bool) $data['is_active'] : false;

        event('social-commerce.channel.create.before', $data);

        $platform = $this->socialChannelPlatformRepository->create($data);

        event('social-commerce.channel.create.after', $platform);

        session()->flash('success', trans('social-commerce::app.admin.social-channels.create.success'));

        return redirect()->route('admin.social-commerce.channels.index');
    }

    public function edit(int $id): View
    {
        $platform = $this->socialChannelPlatformRepository->findOrFail($id);
        $channels = $this->channelRepository->all();

        return view('social-commerce::admin.social-channels.edit', compact('platform', 'channels'));
    }

    public function update(int $id): RedirectResponse
    {
        $data = request()->validate([
            'channel_id'    => 'required|integer|exists:channels,id',
            'platform'      => 'required|in:facebook,instagram,tiktok,youtube,whatsapp',
            'is_active'     => 'boolean',
            'page_url'      => 'nullable|url',
            'page_id'       => 'nullable|string|max:255',
            'pixel_id'      => 'nullable|string|max:255',
            'app_id'        => 'nullable|string',
            'app_secret'    => 'nullable|string',
            'access_token'  => 'nullable|string',
            'catalog_id'    => 'nullable|string|max:255',
            'phone_number_id' => 'nullable|string|max:255',
        ]);

        $data['is_active'] = isset($data['is_active']) ? (bool) $data['is_active'] : false;

        event('social-commerce.channel.update.before', $data);

        $platform = $this->socialChannelPlatformRepository->update($data, $id);

        event('social-commerce.channel.update.after', $platform);

        session()->flash('success', trans('social-commerce::app.admin.social-channels.edit.success'));

        return redirect()->route('admin.social-commerce.channels.index');
    }

    public function destroy(int $id): RedirectResponse
    {
        $platform = $this->socialChannelPlatformRepository->findOrFail($id);

        event('social-commerce.channel.delete.before', $platform);

        $platform->delete();

        event('social-commerce.channel.delete.after', $platform);

        session()->flash('success', trans('social-commerce::app.admin.social-channels.delete.success'));

        return redirect()->route('admin.social-commerce.channels.index');
    }

    public function sync(int $id): RedirectResponse
    {
        $platform = $this->socialChannelPlatformRepository->findOrFail($id);

        SyncProductsToPlatform::dispatch($platform->id);

        session()->flash('success', trans('social-commerce::app.admin.social-channels.sync.queued'));

        return redirect()->route('admin.social-commerce.channels.index');
    }
}
