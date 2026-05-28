<?php

namespace Webkul\Admin\Http\Controllers\Marketing;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class ReferralController extends Controller
{
    public function index()
    {
        $referrals = DB::table('customer_referrals as r')
            ->join('customers as c', 'r.customer_id', '=', 'c.id')
            ->select('r.*', DB::raw("CONCAT(c.first_name, ' ', c.last_name) as customer_name"), 'c.email')
            ->orderByDesc('r.total_earned')
            ->paginate(20);

        return view('admin::marketing.referral.index', compact('referrals'));
    }
}
