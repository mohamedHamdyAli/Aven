<?php

namespace Webkul\AbandonedCart\Services;

use Illuminate\Support\Facades\DB;

class FraudScoringService
{
    /**
     * Score a cart/order for fraud likelihood (0–100).
     * Higher = more suspicious.
     *
     * @param  object  $cart  stdClass or Eloquent cart row
     * @param  array   $orderData  order data array being built
     */
    public function score(object $cart, array $orderData = []): int
    {
        $score = 0;
        $email = $cart->customer_email ?? ($orderData['customer_email'] ?? null);

        if ($email && $this->isDisposableEmail($email)) {
            $score += 25;
        }

        if ($email && $this->exceedsVelocity('email', $email)) {
            $score += 20;
        }

        if ($this->exceedsVelocity('ip', request()->ip())) {
            $score += 15;
        }

        if ($this->hasBillingIpCountryMismatch($orderData)) {
            $score += 10;
        }

        if ($this->isHighValueAnomaly($cart, $email)) {
            $score += 10;
        }

        if ($this->hasMultipleFailedPayments($cart->id ?? null)) {
            $score += 15;
        }

        if ($email && ! $this->hasPriorOrders($email)) {
            $score += 5;
        }

        return min($score, 100);
    }

    private function isDisposableEmail(string $email): bool
    {
        $domain = strtolower(substr(strrchr($email, '@'), 1));
        $blocklist = config('abandoned-cart.disposable_domains', []);

        return in_array($domain, $blocklist, true);
    }

    private function exceedsVelocity(string $type, ?string $value): bool
    {
        if (! $value) {
            return false;
        }

        $windowMinutes = (int) (core()->getConfigData('sales.abandoned_cart.fraud.velocity_check_window_minutes') ?? 60);
        $maxOrders = (int) (core()->getConfigData('sales.abandoned_cart.fraud.velocity_check_max_orders') ?? 5);

        $column = $type === 'email' ? 'customer_email' : 'cart_ip_address';

        $count = DB::table('orders')
            ->where($column, $value)
            ->where('created_at', '>=', now()->subMinutes($windowMinutes))
            ->count();

        return $count >= $maxOrders;
    }

    private function hasBillingIpCountryMismatch(array $orderData): bool
    {
        $billingCountry = $orderData['billing_address']['country'] ?? null;

        if (! $billingCountry) {
            return false;
        }

        // Lightweight check: use CloudFlare / ipapi headers if available
        $ipCountry = request()->header('CF-IPCountry')
            ?? request()->header('X-Country-Code');

        if (! $ipCountry) {
            return false;
        }

        return strtoupper($ipCountry) !== strtoupper($billingCountry);
    }

    private function isHighValueAnomaly(object $cart, ?string $email): bool
    {
        if (! $email) {
            return false;
        }

        $cartTotal = (float) ($cart->base_grand_total ?? 0);

        if ($cartTotal <= 0) {
            return false;
        }

        $avgOrder = DB::table('orders')
            ->where('customer_email', $email)
            ->avg('base_grand_total');

        if (! $avgOrder) {
            return false;
        }

        return $cartTotal > ($avgOrder * 3);
    }

    private function hasMultipleFailedPayments(?int $cartId): bool
    {
        if (! $cartId) {
            return false;
        }

        // Count orders linked to this cart that failed payment (pending_payment status)
        $failed = DB::table('orders')
            ->where('cart_id', $cartId)
            ->where('status', 'pending_payment')
            ->count();

        return $failed >= 2;
    }

    private function hasPriorOrders(string $email): bool
    {
        return DB::table('orders')
            ->where('customer_email', $email)
            ->whereNotIn('status', ['fraud', 'canceled'])
            ->exists();
    }
}
