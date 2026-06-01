<?php

namespace Webkul\EgyptShipping\Http\Controllers\Shop;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Webkul\EgyptShipping\Models\EgyptGovernorate;

class RateController extends Controller
{
    public function show(string $code): JsonResponse
    {
        $gov = EgyptGovernorate::where('code', $code)
            ->where('is_active', true)
            ->whereNotNull('rate')
            ->first();

        if (! $gov) {
            return response()->json(['rate' => null, 'formatted' => null], 404);
        }

        $rate = (float) $gov->rate;

        return response()->json([
            'rate'      => $rate,
            'formatted' => core()->formatPrice($rate),
            'label'     => $gov->name_en . ' — ' . $gov->name_ar,
        ]);
    }
}
