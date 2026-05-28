<?php

namespace Webkul\Fawry\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class FawryController extends Controller
{
    public function redirect()
    {
        $merchantCode = core()->getConfigData('sales.payment_methods.fawry.merchant_code');
        $securityKey  = core()->getConfigData('sales.payment_methods.fawry.security_key');
        $isSandbox    = core()->getConfigData('sales.payment_methods.fawry.sandbox');
        $paymentMethod = core()->getConfigData('sales.payment_methods.fawry.payment_method') ?? 'PAYATFAWRY';

        $order = DB::table('orders')
            ->where('customer_id', auth()->guard('customer')->id())
            ->where('status', 'pending_payment')
            ->orderBy('id', 'desc')
            ->first();

        if (! $order) {
            return redirect()->route('shop.checkout.cart.index')->with('error', 'Order not found.');
        }

        $merchantRef    = 'ORD-'.$order->id;
        $customerProfile = $order->customer_email;
        $expiry         = now()->addDays(3)->timestamp;
        $amount         = number_format((float) $order->grand_total, 2, '.', '');

        $signature = hash('sha256', $merchantCode.$merchantRef.$customerProfile.$expiry.$amount.'1'.$amount.$securityKey);

        $baseUrl = $isSandbox
            ? 'https://atfawry.staging.com/ECommerceWeb/Fawry/payments/charge'
            : 'https://www.atfawry.com/ECommerceWeb/Fawry/payments/charge';

        $payload = [
            'merchantCode'          => $merchantCode,
            'merchantRefNumber'     => $merchantRef,
            'customerProfileId'     => $customerProfile,
            'paymentExpiry'         => $expiry,
            'chargeItems'           => [[
                'itemId'      => 'ORDER-'.$order->id,
                'description' => 'Order #'.$order->increment_id,
                'price'       => $amount,
                'quantity'    => 1,
            ]],
            'paymentMethod'         => $paymentMethod,
            'returnUrl'             => route('fawry.callback'),
            'authCaptureModePayment' => false,
            'signature'             => $signature,
        ];

        $response = Http::post($baseUrl, $payload)->json();

        DB::table('fawry_transactions')->insert([
            'order_id'    => $order->id,
            'fawry_ref'   => $response['referenceNumber'] ?? null,
            'merchant_ref' => $merchantRef,
            'status'      => 'pending',
            'amount'      => $order->grand_total,
            'response'    => json_encode($response),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        if ($paymentMethod === 'PAYATFAWRY') {
            $fawryRef = $response['referenceNumber'] ?? null;

            return view('fawry::redirect', compact('fawryRef', 'order'));
        }

        $redirectUrl = $isSandbox
            ? "https://atfawry.staging.com/ECommerceWeb/atfawry/plugin/payment/index.html?refNum={$merchantRef}&merchantCode={$merchantCode}"
            : "https://www.atfawry.com/ECommerceWeb/atfawry/plugin/payment/index.html?refNum={$merchantRef}&merchantCode={$merchantCode}";

        return redirect($redirectUrl);
    }

    public function callback(Request $request)
    {
        $merchantCode = core()->getConfigData('sales.payment_methods.fawry.merchant_code');
        $securityKey  = core()->getConfigData('sales.payment_methods.fawry.security_key');

        $expectedSig = hash('sha256', $merchantCode.$request->orderRefNumber.$request->paymentAmount.$request->orderStatus.$securityKey);

        if ($request->paymentSignature !== $expectedSig) {
            return redirect()->route('shop.checkout.cart.index')->with('error', 'Payment verification failed.');
        }

        if ($request->orderStatus === 'PAID') {
            DB::table('fawry_transactions')
                ->where('merchant_ref', $request->orderRefNumber)
                ->update(['status' => 'paid', 'updated_at' => now()]);

            return redirect()->route('shop.checkout.onepage.success');
        }

        return redirect()->route('shop.checkout.cart.index')->with('error', 'Payment was not completed: '.$request->statusDescription);
    }
}
