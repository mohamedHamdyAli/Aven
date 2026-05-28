<?php

namespace Webkul\Admin\Http\Controllers\Marketing\Promotions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webkul\Admin\DataGrids\Marketing\Promotions\CouponAssignmentDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\AbandonedCart\Services\WhatsAppService;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;
use Webkul\CartRule\Repositories\CartRuleRepository;

class CouponAssignmentController extends Controller
{
    public function __construct(
        protected CartRuleRepository $cartRuleRepository,
        protected CartRuleCouponRepository $cartRuleCouponRepository,
        protected WhatsAppService $whatsAppService,
    ) {}

    public function index()
    {
        if (request()->ajax()) {
            return app(CouponAssignmentDataGrid::class)->toJson();
        }

        return view('admin::marketing.promotions.coupon-assignments.index');
    }

    public function create()
    {
        $cartRules = $this->cartRuleRepository->findWhere(['coupon_type' => 1]);

        return view('admin::marketing.promotions.coupon-assignments.create', compact('cartRules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'campaign_name' => 'required|string|max:255',
            'cart_rule_id'  => 'required|exists:cart_rules,id',
            'input_type'    => 'required|in:csv,manual',
        ]);

        $cartRule = $this->cartRuleRepository->findOrFail($request->cart_rule_id);

        $phones = $this->resolvePhones($request);

        if (empty($phones)) {
            return back()->withErrors(['phones' => 'No valid phone numbers found.']);
        }

        // Create campaign
        $campaignId = DB::table('coupon_assignment_campaigns')->insertGetId([
            'name'         => $request->campaign_name,
            'cart_rule_id' => $cartRule->id,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        $rows = [];

        foreach ($phones as $phone) {
            $phone = trim($phone);

            if (! $phone) {
                continue;
            }

            $code = $this->cartRuleCouponRepository->getRandomString('alphanumeric', 12);

            $coupon = $this->cartRuleCouponRepository->create([
                'cart_rule_id'       => $cartRule->id,
                'code'               => $code,
                'usage_limit'        => $cartRule->uses_per_coupon ?? 1,
                'usage_per_customer' => $cartRule->usage_per_customer ?? 1,
                'is_primary'         => 0,
                'expired_at'         => $cartRule->ends_till ?: null,
            ]);

            DB::table('cart_rule_coupon_assignments')->insert([
                'campaign_id'         => $campaignId,
                'cart_rule_coupon_id' => $coupon->id,
                'phone'               => $phone,
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);

            $this->whatsAppService->sendTemplate(
                $phone,
                'coupon_assignment',
                [[
                    'type'       => 'body',
                    'parameters' => [
                        ['type' => 'text', 'text' => $code],
                    ],
                ]]
            );

            $rows[] = [$phone, $code];
        }

        return $this->downloadCsv($rows);
    }

    public function show(int $id)
    {
        $campaign = DB::table('coupon_assignment_campaigns as cac')
            ->join('cart_rules as cr', 'cr.id', '=', 'cac.cart_rule_id')
            ->select(
                'cac.id',
                'cac.name as campaign_name',
                'cac.created_at',
                'cr.name as cart_rule_name',
                'cr.discount_amount',
                'cr.action_type',
                'cr.ends_till',
            )
            ->where('cac.id', $id)
            ->first();

        abort_if(! $campaign, 404);

        $assignments = DB::table('cart_rule_coupon_assignments as cca')
            ->join('cart_rule_coupons as crc', 'crc.id', '=', 'cca.cart_rule_coupon_id')
            ->select(
                'cca.id',
                'cca.phone',
                'crc.code as coupon_code',
                'crc.times_used',
                'cca.created_at',
            )
            ->where('cca.campaign_id', $id)
            ->orderBy('cca.id')
            ->get();

        return view('admin::marketing.promotions.coupon-assignments.show', compact('campaign', 'assignments'));
    }

    public function destroy(int $id)
    {
        $campaign = DB::table('coupon_assignment_campaigns')->where('id', $id)->first();

        if ($campaign) {
            $assignmentIds = DB::table('cart_rule_coupon_assignments')
                ->where('campaign_id', $id)
                ->pluck('cart_rule_coupon_id');

            DB::table('cart_rule_coupon_assignments')->where('campaign_id', $id)->delete();

            foreach ($assignmentIds as $couponId) {
                $this->cartRuleCouponRepository->delete($couponId);
            }

            DB::table('coupon_assignment_campaigns')->where('id', $id)->delete();
        }

        return response()->json(['message' => 'Campaign deleted.']);
    }

    public function massDestroy(Request $request)
    {
        $ids = $request->input('indices', []);

        foreach ($ids as $id) {
            $assignmentIds = DB::table('cart_rule_coupon_assignments')
                ->where('campaign_id', $id)
                ->pluck('cart_rule_coupon_id');

            DB::table('cart_rule_coupon_assignments')->where('campaign_id', $id)->delete();

            foreach ($assignmentIds as $couponId) {
                $this->cartRuleCouponRepository->delete($couponId);
            }

            DB::table('coupon_assignment_campaigns')->where('id', $id)->delete();
        }

        return response()->json(['message' => 'Campaigns deleted.']);
    }

    private function resolvePhones(Request $request): array
    {
        if ($request->input_type === 'csv' && $request->hasFile('csv_file')) {
            $content = file_get_contents($request->file('csv_file')->getRealPath());

            // Strip UTF-8 BOM if present
            $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);

            $lines = preg_split('/\r\n|\r|\n/', trim($content));

            $phones = [];

            foreach ($lines as $i => $line) {
                $line = trim($line);

                if ($line === '') {
                    continue;
                }

                // Auto-detect delimiter
                $delimiter = ',';
                foreach (["\t", ';', '|'] as $d) {
                    if (str_contains($line, $d)) {
                        $delimiter = $d;
                        break;
                    }
                }

                $parts = str_getcsv($line, $delimiter);
                $val   = trim($parts[0] ?? '');

                // Skip header row
                if ($i === 0 && $val !== '' && ! is_numeric(str_replace(['+', '-', ' '], '', $val))) {
                    continue;
                }

                if ($val !== '') {
                    $phones[] = $val;
                }
            }

            return $phones;
        }

        $raw = $request->input('phones_raw', '');

        return array_values(array_filter(
            array_map('trim', preg_split('/[\r\n,]+/', $raw))
        ));
    }

    private function downloadCsv(array $rows): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Phone / ID', 'Coupon Code']);
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, 'coupon-assignments-'.now()->format('Y-m-d').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
