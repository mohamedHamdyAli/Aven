<?php

namespace Webkul\Shop\Http\Controllers\API;

use Illuminate\Http\Request;
use Webkul\Customer\Services\LoyaltyService;
use Webkul\Shop\Http\Controllers\Controller;

class LoyaltyController extends Controller
{
    public function __construct(protected LoyaltyService $loyalty) {}

    public function balance()
    {
        $customer = auth()->guard('customer')->user();

        if (! $customer) {
            return response()->json(['balance' => 0, 'value' => core()->formatPrice(0)]);
        }

        $balance = $this->loyalty->getBalance($customer->id);

        return response()->json([
            'balance'   => $balance,
            'value'     => $this->loyalty->pointsValueFormatted($balance),
            'min_redeem'=> $this->loyalty->minRedeem(),
            'enabled'   => $this->loyalty->isEnabled(),
        ]);
    }

    public function redeem(Request $request)
    {
        $customer = auth()->guard('customer')->user();

        if (! $customer) {
            return response()->json(['success' => false, 'message' => 'Please log in to redeem points.'], 401);
        }

        $request->validate(['points' => 'required|integer|min:1']);

        $result = $this->loyalty->redeem($customer->id, (int) $request->points);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function transactions()
    {
        $customer = auth()->guard('customer')->user();

        if (! $customer) {
            return response()->json(['data' => []]);
        }

        return response()->json([
            'data' => $this->loyalty->getTransactions($customer->id)->toArray(),
        ]);
    }
}
