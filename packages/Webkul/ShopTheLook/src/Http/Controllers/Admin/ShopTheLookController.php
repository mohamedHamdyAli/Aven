<?php

namespace Webkul\ShopTheLook\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\ShopTheLook\Models\ProductLookItem;

class ShopTheLookController extends Controller
{
    public function items(int $productId): JsonResponse
    {
        $items = ProductLookItem::with('lookProduct.productFlat')
            ->where('product_id', $productId)
            ->orderBy('sort_order')
            ->get()
            ->map(fn($item) => [
                'id'    => $item->look_product_id,
                'name'  => $item->lookProduct?->productFlat?->name ?? $item->lookProduct?->sku,
                'sku'   => $item->lookProduct?->sku,
                'image' => $item->lookProduct?->base_image?->url,
            ]);

        return response()->json($items);
    }

    public function search(Request $request): JsonResponse
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

        return response()->json($query->orderBy('pf.name')->limit(20)->get());
    }

    public function sync(Request $request, int $productId): JsonResponse
    {
        $ids = array_unique(array_filter((array) $request->input('product_ids', [])));

        ProductLookItem::where('product_id', $productId)->delete();

        foreach (array_values($ids) as $i => $lookId) {
            if ((int) $lookId === $productId) continue;
            ProductLookItem::create([
                'product_id'      => $productId,
                'look_product_id' => (int) $lookId,
                'sort_order'      => $i,
            ]);
        }

        return response()->json(['success' => true]);
    }
}
