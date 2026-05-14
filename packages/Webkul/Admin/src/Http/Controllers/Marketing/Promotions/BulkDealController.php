<?php

namespace Webkul\Admin\Http\Controllers\Marketing\Promotions;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Marketing\Promotions\BulkDealDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\BulkDeal\Repositories\BulkDealRepository;

class BulkDealController extends Controller
{
    public function __construct(protected BulkDealRepository $bulkDealRepository) {}

    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(BulkDealDataGrid::class)->process();
        }

        return view('admin::marketing.promotions.bulk-deals.index');
    }

    public function create(): View
    {
        return view('admin::marketing.promotions.bulk-deals.create');
    }

    public function store(): \Illuminate\Http\RedirectResponse
    {
        $this->validate(request(), [
            'name'          => 'required|string|max:255',
            'paid_quantity' => 'required|integer|min:0',
            'deal_quantity' => 'required|integer|min:1',
            'deal_price'    => 'required|numeric|min:0',
            'starts_from'   => 'nullable|date',
            'ends_till'     => 'nullable|date|after_or_equal:starts_from',
            'sort_order'    => 'nullable|integer|min:0',
        ]);

        $this->bulkDealRepository->create(request()->only([
            'name', 'description', 'status', 'paid_quantity',
            'deal_quantity', 'deal_price', 'starts_from', 'ends_till', 'sort_order',
        ]));

        session()->flash('success', trans('admin::app.marketing.promotions.bulk-deals.create.create-success'));

        return redirect()->route('admin.marketing.promotions.bulk_deals.index');
    }

    public function edit(int $id): View
    {
        $bulkDeal = $this->bulkDealRepository->findOrFail($id);

        return view('admin::marketing.promotions.bulk-deals.edit', compact('bulkDeal'));
    }

    public function update(int $id): \Illuminate\Http\RedirectResponse
    {
        $this->validate(request(), [
            'name'          => 'required|string|max:255',
            'paid_quantity' => 'required|integer|min:0',
            'deal_quantity' => 'required|integer|min:1',
            'deal_price'    => 'required|numeric|min:0',
            'starts_from'   => 'nullable|date',
            'ends_till'     => 'nullable|date|after_or_equal:starts_from',
            'sort_order'    => 'nullable|integer|min:0',
        ]);

        $this->bulkDealRepository->update(request()->only([
            'name', 'description', 'status', 'paid_quantity',
            'deal_quantity', 'deal_price', 'starts_from', 'ends_till', 'sort_order',
        ]), $id);

        session()->flash('success', trans('admin::app.marketing.promotions.bulk-deals.edit.update-success'));

        return redirect()->route('admin.marketing.promotions.bulk_deals.index');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->bulkDealRepository->findOrFail($id);

        $this->bulkDealRepository->delete($id);

        return new JsonResponse([
            'message' => trans('admin::app.marketing.promotions.bulk-deals.delete-success'),
        ]);
    }
}
