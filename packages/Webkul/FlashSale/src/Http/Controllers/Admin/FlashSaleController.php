<?php

namespace Webkul\FlashSale\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Webkul\FlashSale\Models\FlashSale;
use Webkul\FlashSale\Services\FlashSaleService;
use Webkul\Product\Repositories\ProductRepository;

class FlashSaleController extends Controller
{
    public function __construct(
        private FlashSaleService $service,
        private ProductRepository $productRepository
    ) {}

    public function index(): View
    {
        $sales = FlashSale::withCount('products')->orderByDesc('id')->paginate(20);

        return view('flash-sale::admin.flash-sales.index', compact('sales'));
    }

    public function create(): View
    {
        return view('flash-sale::admin.flash-sales.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'discount_percent' => 'required|numeric|min:1|max:99',
            'starts_at'        => 'required|date',
            'ends_at'          => 'required|date|after:starts_at',
            'product_ids'      => 'required|array|min:1',
            'product_ids.*'    => 'integer|exists:products,id',
        ]);

        $sale = FlashSale::create([
            'name'             => $data['name'],
            'discount_percent' => $data['discount_percent'],
            'starts_at'        => $data['starts_at'],
            'ends_at'          => $data['ends_at'],
            'active'           => false,
        ]);

        $sale->products()->sync($data['product_ids']);

        if (now()->between($sale->starts_at, $sale->ends_at)) {
            $this->service->apply($sale);
            $sale->update(['active' => true]);
        }

        session()->flash('success', 'Flash sale created successfully.');

        return redirect()->route('admin.marketing.flash-sales.index');
    }

    public function destroy(int $id): RedirectResponse
    {
        $sale = FlashSale::findOrFail($id);
        $this->service->expire($sale);
        $sale->delete();

        session()->flash('success', 'Flash sale deleted.');

        return redirect()->route('admin.marketing.flash-sales.index');
    }
}
