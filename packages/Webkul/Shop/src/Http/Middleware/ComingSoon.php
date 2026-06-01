<?php

namespace Webkul\Shop\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ComingSoon
{
    public function handle(Request $request, Closure $next)
    {
        if (! core()->getConfigData('general.content.coming_soon.enabled')) {
            return $next($request);
        }

        if ($request->is('coming-soon')) {
            return $next($request);
        }

        return redirect()->route('shop.coming_soon');
    }
}
