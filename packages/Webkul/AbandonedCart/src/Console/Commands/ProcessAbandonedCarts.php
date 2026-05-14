<?php

namespace Webkul\AbandonedCart\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Webkul\AbandonedCart\Jobs\SendAbandonedCartNotification;

class ProcessAbandonedCarts extends Command
{
    protected $signature = 'abandoned-cart:process {--dry-run : List eligible carts without dispatching jobs}';

    protected $description = 'Dispatch recovery notifications for abandoned carts and expire over-limit carts.';

    public function handle(): int
    {
        if (! core()->getConfigData('sales.abandoned_cart.general.enabled')) {
            $this->info('Abandoned cart recovery is disabled in configuration.');

            return self::SUCCESS;
        }

        $thresholdMinutes = (int) (core()->getConfigData('sales.abandoned_cart.general.inactivity_threshold_minutes') ?? 60);
        $maxAttempts = (int) (core()->getConfigData('sales.abandoned_cart.general.max_notification_attempts') ?? 3);
        $autoCancel = (bool) core()->getConfigData('sales.abandoned_cart.general.auto_cancel_after_max_attempts');
        $requireApproval = (bool) (core()->getConfigData('sales.abandoned_cart.general.require_admin_approval_to_cancel') ?? true);

        $cutoff = now()->subMinutes($thresholdMinutes);

        // --- Step 1: Find carts ready for NEXT notification attempt ---
        $carts = DB::table('cart')
            ->where('is_active', 1)
            ->whereIn('recovery_status', ['active', 'recovering'])
            ->where('last_activity_at', '<=', $cutoff)
            ->where('notification_count', '<', $maxAttempts)
            ->where(function ($q) {
                $q->whereNotNull('customer_email')
                  ->orWhereNotNull('customer_id');
            })
            ->select('id', 'notification_count', 'recovery_status', 'customer_email')
            ->get();

        $dispatched = 0;

        foreach ($carts as $cart) {
            $nextAttempt = $cart->notification_count + 1;

            if (! $this->isDelayElapsed($cart->id, $nextAttempt)) {
                continue;
            }

            if ($this->option('dry-run')) {
                $this->line("  [dry-run] Cart #{$cart->id} → attempt #{$nextAttempt} ({$cart->customer_email})");
                continue;
            }

            SendAbandonedCartNotification::dispatch($cart->id, $nextAttempt);
            $dispatched++;
        }

        // --- Step 2: Handle carts that have exhausted all attempts ---
        $exhausted = DB::table('cart')
            ->where('is_active', 1)
            ->where('recovery_status', 'recovering')
            ->where('notification_count', '>=', $maxAttempts)
            ->where('last_activity_at', '<=', $cutoff)
            ->pluck('id');

        foreach ($exhausted as $cartId) {
            if ($autoCancel && ! $requireApproval) {
                DB::table('cart')->where('id', $cartId)->update([
                    'is_active'       => 0,
                    'recovery_status' => 'expired',
                ]);

                if (! $this->option('dry-run')) {
                    $this->line("  Cart #{$cartId} auto-cancelled after {$maxAttempts} attempts.");
                }
            } else {
                DB::table('cart')->where('id', $cartId)->update(['recovery_status' => 'expired']);

                if (! $this->option('dry-run')) {
                    $this->line("  Cart #{$cartId} marked expired — awaiting admin approval to cancel.");
                }
            }
        }

        $this->info("Done. Dispatched {$dispatched} notification job(s). Exhausted: ".count($exhausted).'.');

        return self::SUCCESS;
    }

    private function isDelayElapsed(int $cartId, int $attempt): bool
    {
        $delayHoursKey = "sales.abandoned_cart.general.attempt_{$attempt}_delay_hours";
        $delayHours = (int) (core()->getConfigData($delayHoursKey) ?? match ($attempt) {
            1 => 1,
            2 => 24,
            default => 72,
        });

        $lastSent = DB::table('abandoned_cart_notifications')
            ->where('cart_id', $cartId)
            ->whereIn('status', ['sent', 'opened', 'clicked'])
            ->max('sent_at');

        if (! $lastSent) {
            return true;
        }

        return now()->diffInHours($lastSent) >= $delayHours;
    }
}
