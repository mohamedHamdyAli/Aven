<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Routing\Controller;

class ComingSoonController extends Controller
{
    public function index()
    {
        if (! core()->getConfigData('general.content.coming_soon.enabled')) {
            return redirect()->route('shop.home.index');
        }

        return view('shop::coming-soon');
    }
}
