<?php

namespace Webkul\Admin\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class LoyaltyController extends Controller
{
    public function index()
    {
        if (request()->wantsJson()) {
            $data = DB::table('customer_loyalty_points as lp')
                ->join('customers as c', 'lp.customer_id', '=', 'c.id')
                ->select('lp.*', DB::raw("CONCAT(c.first_name, ' ', c.last_name) as customer_name"), 'c.email')
                ->orderByDesc('lp.balance')
                ->paginate(20);

            return response()->json($data);
        }

        return view('admin::marketing.loyalty.index');
    }

    public function adjust(Request $request, int $customerId)
    {
        $request->validate([
            'points' => 'required|numeric',
            'note'   => 'nullable|string|max:255',
        ]);

        $service = app(\Webkul\Loyalty\Services\LoyaltyService::class);
        $points = (float) $request->points;

        if ($points > 0) {
            $service->credit($customerId, $points, null, 'admin', $request->note ?? 'Admin adjustment');
        } else {
            $service->debit($customerId, abs($points), null, $request->note ?? 'Admin adjustment');
        }

        return back()->with('success', 'Points adjusted.');
    }
}
