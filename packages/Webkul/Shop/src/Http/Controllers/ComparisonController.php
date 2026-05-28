<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class ComparisonController extends Controller
{
    public function index()
    {
        $ids = session('comparison_products', []);
        $products = collect();
        if (count($ids) > 0) {
            $products = DB::table('product_flat')
                ->whereIn('product_id', $ids)
                ->where('locale', app()->getLocale())
                ->where('channel', core()->getCurrentChannelCode())
                ->get()
                ->keyBy('product_id')
                ->values();
        }

        return view('shop::products.compare', compact('products'));
    }

    public function add(Request $request)
    {
        $id = (int) $request->product_id;
        $products = session('comparison_products', []);
        if (! in_array($id, $products)) {
            if (count($products) >= 4) {
                array_shift($products);
            }
            $products[] = $id;
        }
        session(['comparison_products' => $products]);

        return response()->json(['success' => true, 'count' => count($products)]);
    }

    public function remove(Request $request)
    {
        $products = array_values(array_filter(
            session('comparison_products', []),
            fn ($id) => $id != $request->product_id
        ));
        session(['comparison_products' => $products]);

        return response()->json(['success' => true, 'count' => count($products)]);
    }

    public function clear()
    {
        session()->forget('comparison_products');

        return redirect()->back();
    }
}
