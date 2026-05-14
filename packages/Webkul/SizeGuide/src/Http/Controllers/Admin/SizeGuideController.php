<?php

namespace Webkul\SizeGuide\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Spatie\ResponseCache\ResponseCache;
use Webkul\SizeGuide\Repositories\SizeChartRepository;

class SizeGuideController extends Controller
{
    public function __construct(protected SizeChartRepository $repo) {}

    public function index()
    {
        $charts = $this->repo->all();
        return view('size-guide::admin.size-guide.index', compact('charts'));
    }

    public function create()
    {
        return view('size-guide::admin.size-guide.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'gender'       => 'required|in:mens,womens,kids,unisex',
            'type'         => 'required|in:tops,bottoms,footwear,full-body,other',
            'image'        => 'nullable|image|max:4096',
            'rows'         => 'array',
            'rows.*.label' => 'required_with:rows|string|max:20',
            'products'     => 'array',
            'products.*'   => 'integer',
        ]);

        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('size-guides', 'public')
            : null;

        $chart = $this->repo->create($request->all(), $imagePath);
        $this->repo->syncProducts($chart->id, $request->input('products', []));
        ResponseCache::clear();

        return redirect()->route('admin.size-guide.index')
            ->with('success', __('size-guide::app.admin.size-guide.created'));
    }

    public function edit(int $id)
    {
        $chart            = $this->repo->find($id);
        $assignedProducts = $this->repo->assignedProducts($id);
        return view('size-guide::admin.size-guide.form', compact('chart', 'assignedProducts'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'gender'     => 'required|in:mens,womens,kids,unisex',
            'type'       => 'required|in:tops,bottoms,footwear,full-body,other',
            'image'      => 'nullable|image|max:4096',
            'products'   => 'array',
            'products.*' => 'integer',
        ]);

        $existing   = $this->repo->find($id);
        $clearImage = $request->boolean('remove_image');
        $imagePath  = null;

        if ($request->hasFile('image')) {
            if ($existing?->image) Storage::disk('public')->delete($existing->image);
            $imagePath = $request->file('image')->store('size-guides', 'public');
        } elseif ($clearImage && $existing?->image) {
            Storage::disk('public')->delete($existing->image);
        }

        $this->repo->update($id, $request->all(), $imagePath, $clearImage);
        $this->repo->syncProducts($id, $request->input('products', []));
        ResponseCache::clear();

        return redirect()->route('admin.size-guide.index')
            ->with('success', __('size-guide::app.admin.size-guide.updated'));
    }

    public function destroy(int $id): JsonResponse
    {
        $chart = $this->repo->find($id);
        if ($chart?->image) Storage::disk('public')->delete($chart->image);
        $this->repo->delete($id);
        ResponseCache::clear();
        return response()->json(['success' => true]);
    }

    /** Live product search for the size guide form */
    public function searchProducts(Request $request): JsonResponse
    {
        $q = trim($request->input('q', ''));

        $query = \DB::table('products as p')
            ->join('product_flat as pf', function ($j) {
                $j->on('pf.product_id', '=', 'p.id')
                  ->where('pf.locale', config('app.locale', 'en'))
                  ->whereNull('p.parent_id');
            })
            ->select('p.id', 'pf.name', 'p.sku')
            ->whereNull('p.parent_id')
            ->whereNotNull('pf.name');

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('pf.name', 'like', '%'.$q.'%')
                  ->orWhere('p.sku', 'like', '%'.$q.'%');
            });
        }

        $results = $query->orderBy('pf.name')->limit(20)->get();

        return response()->json($results);
    }

    /** Assign a size chart to a product (called from product edit page sidebar) */
    public function assignProduct(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer',
            'chart_id'   => 'nullable|integer',
        ]);

        $this->repo->assignToProduct($request->product_id, $request->chart_id ?: null);

        return response()->json(['success' => true]);
    }
}
