<?php

namespace Webkul\Referral\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Webkul\Referral\Models\CustomerReferral;
use Webkul\Referral\Models\ReferralConversion;

class ReferralService
{
    public function getOrCreateCode(int $customerId): string
    {
        $ref = CustomerReferral::firstOrCreate(
            ['customer_id' => $customerId],
            ['referral_code' => $this->generateUniqueCode()]
        );

        return $ref->referral_code;
    }

    private function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (CustomerReferral::where('referral_code', $code)->exists());

        return $code;
    }

    public function getShareUrl(string $code): string
    {
        return url('/ref/'.$code);
    }

    public function trackVisit(string $code): bool
    {
        return CustomerReferral::where('referral_code', $code)->exists();
    }

    public function recordRegistration(string $code, int $referredCustomerId, string $email): void
    {
        $referral = CustomerReferral::where('referral_code', $code)->first();

        if (! $referral) {
            return;
        }

        ReferralConversion::firstOrCreate(
            ['referred_customer_id' => $referredCustomerId],
            [
                'referral_code'        => $code,
                'referrer_customer_id' => $referral->customer_id,
                'referred_email'       => $email,
                'status'               => 'pending',
            ]
        );
    }

    public function rewardOnFirstOrder($order): void
    {
        if (! $order->customer_id) {
            return;
        }

        if (! core()->getConfigData('general.referral.settings.enabled')) {
            return;
        }

        $orderCount = DB::table('orders')->where('customer_id', $order->customer_id)->count();

        if ($orderCount > 1) {
            return; // Not first order
        }

        $conversion = ReferralConversion::where('referred_customer_id', $order->customer_id)
            ->where('status', 'pending')
            ->first();

        if (! $conversion) {
            return;
        }

        $amount = (float) (core()->getConfigData('general.referral.settings.reward_amount') ?? 50);
        $type = core()->getConfigData('general.referral.settings.reward_type') ?? 'wallet_credit';

        if ($type === 'wallet_credit') {
            $balance = (float) DB::table('customer_wallets')
                ->where('customer_id', $conversion->referrer_customer_id)
                ->value('balance');

            if (DB::table('customer_wallets')->where('customer_id', $conversion->referrer_customer_id)->exists()) {
                DB::table('customer_wallets')
                    ->where('customer_id', $conversion->referrer_customer_id)
                    ->increment('balance', $amount);
            } else {
                DB::table('customer_wallets')->insert([
                    'customer_id' => $conversion->referrer_customer_id,
                    'balance'     => $amount,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }

            DB::table('customer_wallet_transactions')->insert([
                'customer_id'    => $conversion->referrer_customer_id,
                'type'           => 'credit',
                'amount'         => $amount,
                'balance_after'  => $balance + $amount,
                'description'    => 'Referral reward — friend placed first order',
                'reference_id'   => $order->id,
                'reference_type' => 'referral',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        $conversion->update([
            'order_id'    => $order->id,
            'status'      => 'rewarded',
            'rewarded_at' => now(),
        ]);

        CustomerReferral::where('referral_code', $conversion->referral_code)
            ->increment('times_used');

        CustomerReferral::where('referral_code', $conversion->referral_code)
            ->increment('total_earned', $amount);
    }
}
