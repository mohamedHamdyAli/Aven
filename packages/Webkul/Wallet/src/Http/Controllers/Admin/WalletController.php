<?php

namespace Webkul\Wallet\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\Customer\Models\Customer;
use Webkul\Wallet\Models\CustomerWallet;
use Webkul\Wallet\Models\CustomerWalletTransaction;
use Webkul\Wallet\Services\WalletService;

class WalletController extends Controller
{
    public function __construct(protected WalletService $wallet) {}

    public function index()
    {
        $wallets = CustomerWallet::with('transactions')
            ->orderByDesc('balance')
            ->paginate(25);

        return view('wallet::admin.index', compact('wallets'));
    }

    public function customer(int $customerId)
    {
        $customer     = Customer::findOrFail($customerId);
        $balance      = $this->wallet->balance($customerId);
        $transactions = $this->wallet->transactions($customerId);

        return view('wallet::admin.customer', compact('customer', 'balance', 'transactions'));
    }

    public function issue(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount'      => 'required|numeric|min:0.01',
            'note'        => 'nullable|string|max:255',
        ]);

        $this->wallet->credit((int) $data['customer_id'], (float) $data['amount'], $data['note'] ?? 'Admin credit');

        session()->flash('success', 'Store credit issued successfully.');

        return back();
    }

    public function revoke(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount'      => 'required|numeric|min:0.01',
            'note'        => 'nullable|string|max:255',
        ]);

        $ok = $this->wallet->debit((int) $data['customer_id'], (float) $data['amount'], $data['note'] ?? 'Admin debit');

        if (! $ok) {
            session()->flash('error', 'Insufficient balance.');
        } else {
            session()->flash('success', 'Store credit revoked.');
        }

        return back();
    }
}
