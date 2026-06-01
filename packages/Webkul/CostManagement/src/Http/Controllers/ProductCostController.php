<?php

namespace Webkul\CostManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\CostManagement\DataGrids\ProductCostDataGrid;
use Webkul\CostManagement\Models\ProductCost;
use Webkul\Product\Repositories\ProductRepository;

class ProductCostController extends Controller
{
    public function __construct(protected ProductRepository $productRepository) {}

    public function index()
    {
        if (request()->ajax()) {
            return datagrid(ProductCostDataGrid::class)->process();
        }

        return view('cost_management::products.index');
    }

    public function edit(int $productId)
    {
        $product = $this->productRepository->findOrFail($productId);
        $cost    = ProductCost::firstOrNew(['product_id' => $productId]);

        return view('cost_management::products.edit', compact('product', 'cost'));
    }

    public function update(Request $request, int $productId)
    {
        $data = $request->validate([
            'cost_price'             => 'required|numeric|min:0',
            'manufacturing_fee'      => 'nullable|numeric|min:0',
            'shipping_cost_per_unit' => 'nullable|numeric|min:0',
            'other_costs'            => 'nullable|numeric|min:0',
            'notes'                  => 'nullable|string|max:1000',
        ]);

        $data['product_id'] = $productId;

        $cost = ProductCost::updateOrCreate(
            ['product_id' => $productId],
            $data
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'cost' => $cost]);
        }

        session()->flash('success', 'Product cost saved successfully.');

        return redirect()->route('admin.cost_management.products.index');
    }
}
