<?php

namespace Webkul\Valu\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class ValuController extends Controller
{
    public function redirect()
    {
        $merchantId = core()->getConfigData('sales.payment_methods.valu.merchant_id');
        $isSandbox  = core()->getConfigData('sales.payment_methods.valu.sandbox');

        // Get the last pending_payment order for this customer
        $customerId = auth()->guard('customer')->id();

        $order = DB::table('orders')
            ->where(function ($q) use ($customerId) {
                if ($customerId) {
                    $q->where('customer_id', $customerId);
                } else {
                    $q->where('customer_email', session('order_email'));
                }
            })
            ->whereIn('status', ['pending', 'pending_payment'])
            ->orderByDesc('id')
            ->first();

        if (! $order) {
            return redirect()->route('shop.checkout.cart.index')->with('error', 'Order not found.');
        }

        $transactionId = 'ORD-'.$order->id;
        $amount        = number_format((float) $order->grand_total, 2, '.', '');
        $callbackUrl   = route('valu.callback');
        $baseUrl       = $isSandbox
            ? 'https://sandbox.merchant.valu.com.eg/checkout'
            : 'https://merchant.valu.com.eg/checkout';

        $valuUrl = $baseUrl.'?'.http_build_query([
            'merchant_id'    => $merchantId,
            'amount'         => $amount,
            'transaction_id' => $transactionId,
            'callback_url'   => $callbackUrl,
        ]);

        DB::table('valu_transactions')->insert([
            'order_id'     => $order->id,
            'merchant_ref' => $transactionId,
            'status'       => 'pending',
            'amount'       => $order->grand_total,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return view('valu::redirect', compact('valuUrl', 'order'));
    }

    public function callback(Request $request)
    {
        $transactionId = $request->transaction_id;
        $status        = strtoupper($request->status ?? '');
        $valRef        = $request->reference_number ?? null;

        if ($transactionId) {
            DB::table('valu_transactions')
                ->where('merchant_ref', $transactionId)
                ->update([
                    'valu_ref'   => $valRef,
                    'status'     => $status === 'SUCCESS' ? 'paid' : 'failed',
                    'updated_at' => now(),
                ]);
        }

        if ($status === 'SUCCESS') {
            return redirect()->route('shop.checkout.onepage.success');
        }

        return redirect()->route('shop.checkout.cart.index')
            ->with('error', 'Valu payment was not completed. Please try again.');
    }
}
