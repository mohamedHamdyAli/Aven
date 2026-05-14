<?php

namespace Webkul\AbandonedCart\Listeners;

use Webkul\AbandonedCart\Services\FraudScoringService;

class FraudOrderListener
{
    public function __construct(protected FraudScoringService $fraudScoring) {}

    /**
     * Score the order's source cart before the order record is saved.
     * Mutates $data['status'] and injects fraud_score into payment additional.
     */
    public function scoreOrder(array &$data): void
    {
        if (! core()->getConfigData('sales.abandoned_cart.fraud.fraud_detection_enabled')) {
            return;
        }

        $cartId = $data['cart_id'] ?? null;

        if (! $cartId) {
            return;
        }

        $cart = \DB::table('cart')->where('id', $cartId)->first();

        if (! $cart) {
            return;
        }

        $score = $this->fraudScoring->score((object) $cart, $data);

        // Store score in payment additional JSON
        if (isset($data['payment']['additional'])) {
            $additional = is_string($data['payment']['additional'])
                ? json_decode($data['payment']['additional'], true)
                : (array) $data['payment']['additional'];
        } else {
            $additional = [];
        }

        $additional['fraud_score'] = $score;
        $data['payment']['additional'] = $additional;

        $threshold = (int) (core()->getConfigData('sales.abandoned_cart.fraud.auto_flag_score_threshold') ?? 60);

        if ($score >= $threshold) {
            $data['status'] = 'fraud';
        } elseif ($score >= 31) {
            // Medium risk — add internal comment for admin review
            $data['_fraud_comment'] = sprintf(
                'Risk score %d/100 — flagged for manual review.',
                $score
            );
        }
    }
}
