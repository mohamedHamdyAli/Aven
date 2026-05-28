<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WishlistShareController extends Controller
{
    public function generate()
    {
        $customer = auth()->guard('customer')->user();
        if (! $customer->share_token) {
            DB::table('customers')->where('id', $customer->id)->update(['share_token' => Str::random(48)]);
            $customer->share_token = DB::table('customers')->where('id', $customer->id)->value('share_token');
        }

        return response()->json(['url' => route('shop.wishlist.share.view', $customer->share_token)]);
    }

    public function view(string $token)
    {
        $customer = DB::table('customers')->where('share_token', $token)->first();
        if (! $customer) {
            abort(404);
        }

        $items = DB::table('wishlist_items')
            ->where('wishlist_items.customer_id', $customer->id)
            ->join('product_flat', function ($j) {
                $j->on('wishlist_items.product_id', '=', 'product_flat.product_id')
                    ->where('product_flat.locale', app()->getLocale())
                    ->where('product_flat.channel', core()->getCurrentChannelCode());
            })
            ->select('product_flat.product_id', 'product_flat.name', 'product_flat.price', 'product_flat.url_key')
            ->get();

        return view('shop::customers.account.wishlist.shared', compact('customer', 'items'));
    }
}
