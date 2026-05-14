<?php

namespace Webkul\Admin\Http\Controllers\StoreLocator;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\StoreLocator\StoreLocatorDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\StoreLocator\Repositories\StoreLocatorRepository;

class StoreLocatorController extends Controller
{
    public function __construct(protected StoreLocatorRepository $storeLocatorRepository) {}

    public function index(): mixed
    {
        if (request()->ajax()) {
            return datagrid(StoreLocatorDataGrid::class)->process();
        }

        return view('admin::store-locator.index');
    }

    public function create(): View
    {
        return view('admin::store-locator.create');
    }

    public function store(): mixed
    {
        $this->validate(request(), [
            'name'      => 'required|string|max:255',
            'address'   => 'required|string',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'phone'     => 'nullable|string|max:50',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'    => 'sometimes|boolean',
        ]);

        Event::dispatch('store-locator.branch.create.before');

        $data = request()->only(['name', 'address', 'latitude', 'longitude', 'phone', 'status']);
        $data['status'] = request()->boolean('status', true);
        $data['working_hours'] = $this->parseWorkingHours(request()->input('working_hours', []));

        if (request()->hasFile('image')) {
            $data['image'] = request()->file('image');
        }

        $branch = $this->storeLocatorRepository->create($data);

        Event::dispatch('store-locator.branch.create.after', $branch);

        session()->flash('success', trans('admin::app.store-locator.create.success'));

        return redirect()->route('admin.store-locator.index');
    }

    public function edit(int $id): View
    {
        $branch = $this->storeLocatorRepository->findOrFail($id);

        return view('admin::store-locator.edit', compact('branch'));
    }

    public function update(int $id): mixed
    {
        $this->validate(request(), [
            'name'      => 'required|string|max:255',
            'address'   => 'required|string',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'phone'     => 'nullable|string|max:50',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'    => 'sometimes|boolean',
        ]);

        Event::dispatch('store-locator.branch.update.before', $id);

        $data = request()->only(['name', 'address', 'latitude', 'longitude', 'phone', 'status']);
        $data['status'] = request()->boolean('status', true);
        $data['working_hours'] = $this->parseWorkingHours(request()->input('working_hours', []));

        if (request()->hasFile('image')) {
            $data['image'] = request()->file('image');
        }

        $branch = $this->storeLocatorRepository->update($data, $id);

        Event::dispatch('store-locator.branch.update.after', $branch);

        session()->flash('success', trans('admin::app.store-locator.edit.success'));

        return redirect()->route('admin.store-locator.index');
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            Event::dispatch('store-locator.branch.delete.before', $id);

            $this->storeLocatorRepository->delete($id);

            Event::dispatch('store-locator.branch.delete.after', $id);

            return new JsonResponse(['message' => trans('admin::app.store-locator.index.delete-success')]);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => trans('admin::app.store-locator.index.delete-failed')], 500);
        }
    }

    public function massDelete(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        foreach ($massDestroyRequest->input('indices') as $id) {
            Event::dispatch('store-locator.branch.delete.before', $id);

            $this->storeLocatorRepository->delete($id);

            Event::dispatch('store-locator.branch.delete.after', $id);
        }

        return new JsonResponse(['message' => trans('admin::app.store-locator.index.mass-delete-success')]);
    }

    private function parseWorkingHours(array $input): array
    {
        $days = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $hours = [];

        foreach ($days as $day) {
            $hours[$day] = [
                'open' => isset($input[$day]['open']) && $input[$day]['open'] === '1',
                'from' => $input[$day]['from'] ?? null,
                'to'   => $input[$day]['to'] ?? null,
            ];
        }

        return $hours;
    }
}
