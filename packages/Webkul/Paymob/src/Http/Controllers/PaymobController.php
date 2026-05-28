<?php

namespace Webkul\Paymob\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Webkul\Checkout\Facades\Cart;
use Webkul\Paymob\Payment\Paymob;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\OrderTransactionRepository;
use Webkul\Sales\Transformers\OrderResource;

class PaymobController extends Controller
{
    public function __construct(
        protected Paymob $paymob,
        protected OrderRepository $orderRepository,
        protected OrderTransactionRepository $orderTransactionRepository,
        protected InvoiceRepository $invoiceRepository,
    ) {}

    public function redirect(): RedirectResponse
    {
        try {
            $cart = Cart::getCart();

            if (! $cart) {
                session()->flash('error', 'Cart is empty.');
                return redirect()->route('shop.checkout.cart.index');
            }

            $authToken      = $this->paymob->getAuthToken();
            $paymobOrder    = $this->paymob->createOrder($authToken, $cart);
            $paymentToken   = $this->paymob->getPaymentKey($authToken, $paymobOrder['id'], $cart);

            session(['paymob_order_id' => $paymobOrder['id']]);

            return redirect($this->paymob->iframeUrl($paymentToken));
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Payment initiation failed. Please try again.');
            return redirect()->route('shop.checkout.cart.index');
        }
    }

    public function callback(Request $request): RedirectResponse
    {
        $data = $request->all();

        if (! $this->paymob->verifyHmac($data)) {
            session()->flash('error', 'Payment verification failed.');
            return redirect()->route('shop.checkout.cart.index');
        }

        if ($data['success'] !== 'true') {
            session()->flash('error', 'Payment was not successful.');
            return redirect()->route('shop.checkout.cart.index');
        }

        $cart = Cart::getCart();

        if (! $cart) {
            return redirect()->route('shop.checkout.onepage.success');
        }

        try {
            $orderData = (new OrderResource($cart))->jsonSerialize();
            $order     = $this->orderRepository->create($orderData);

            if ($order->payment) {
                $order->payment->update([
                    'additional' => [
                        'status'           => 'captured',
                        'paymob_order_id'  => $data['order']['id'] ?? null,
                        'transaction_id'   => $data['id'] ?? null,
                    ],
                ]);
            }

            $this->orderRepository->update(['status' => 'processing'], $order->id);

            $invoiceData = $this->prepareInvoiceData($order->id);
            if (! empty($invoiceData)) {
                $invoice = $this->invoiceRepository->create($invoiceData);
            }

            $this->orderTransactionRepository->create([
                'transaction_id' => (string) ($data['id'] ?? uniqid()),
                'status'         => 'captured',
                'type'           => 'paymob',
                'payment_method' => 'paymob',
                'order_id'       => $order->id,
                'invoice_id'     => $invoice->id ?? null,
                'amount'         => $order->grand_total,
                'data'           => json_encode($data),
            ]);

            Cart::deActivateCart();

            session()->flash('order_id', $order->id);

            return redirect()->route('shop.checkout.onepage.success');
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Order processing failed.');
            return redirect()->route('shop.checkout.cart.index');
        }
    }

    protected function prepareInvoiceData(int $orderId): array
    {
        try {
            $order = $this->orderRepository->findOrFail($orderId);
            $items = [];

            foreach ($order->items as $item) {
                if ($item->qty_to_invoice > 0) {
                    $items[$item->id] = $item->qty_to_invoice;
                }
            }

            return empty($items) ? [] : ['order_id' => $orderId, 'invoice' => ['items' => $items]];
        } catch (\Throwable) {
            return [];
        }
    }
}
