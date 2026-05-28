<?php

namespace Webkul\Wallet\Services;

use Illuminate\Support\Facades\DB;
use Webkul\Wallet\Models\CustomerWallet;
use Webkul\Wallet\Models\CustomerWalletTransaction;

class WalletService
{
    public function balance(int $customerId): float
    {
        return (float) CustomerWallet::firstOrCreate(
            ['customer_id' => $customerId],
            ['balance' => 0]
        )->balance;
    }

    public function credit(int $customerId, float $amount, string $note = '', ?int $orderId = null): void
    {
        DB::transaction(function () use ($customerId, $amount, $note, $orderId) {
            $wallet = CustomerWallet::firstOrCreate(
                ['customer_id' => $customerId],
                ['balance' => 0]
            );

            $wallet->increment('balance', $amount);
            $wallet->refresh();

            CustomerWalletTransaction::create([
                'customer_id'  => $customerId,
                'order_id'     => $orderId,
                'type'         => 'credit',
                'amount'       => $amount,
                'balance_after' => $wallet->balance,
                'note'         => $note,
            ]);
        });
    }

    public function debit(int $customerId, float $amount, string $note = '', ?int $orderId = null): bool
    {
        return DB::transaction(function () use ($customerId, $amount, $note, $orderId) {
            $wallet = CustomerWallet::where('customer_id', $customerId)->lockForUpdate()->first();

            if (! $wallet || $wallet->balance < $amount) {
                return false;
            }

            $wallet->decrement('balance', $amount);
            $wallet->refresh();

            CustomerWalletTransaction::create([
                'customer_id'  => $customerId,
                'order_id'     => $orderId,
                'type'         => 'debit',
                'amount'       => $amount,
                'balance_after' => $wallet->balance,
                'note'         => $note,
            ]);

            return true;
        });
    }

    public function applyToCart(int $cartId, int $customerId, float $amount): float
    {
        $balance  = $this->balance($customerId);
        $cartTotal = (float) DB::table('cart')->where('id', $cartId)->value('grand_total');

        $apply = min($balance, $cartTotal, $amount);
        $apply = max(0, $apply);

        DB::table('cart')->where('id', $cartId)->update(['store_credit_applied' => $apply]);

        return $apply;
    }

    public function removeFromCart(int $cartId): void
    {
        DB::table('cart')->where('id', $cartId)->update(['store_credit_applied' => 0]);
    }

    public function transactions(int $customerId, int $perPage = 15)
    {
        return CustomerWalletTransaction::where('customer_id', $customerId)
            ->latest()
            ->paginate($perPage);
    }
}
