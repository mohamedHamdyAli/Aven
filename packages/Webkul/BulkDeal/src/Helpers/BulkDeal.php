<?php

namespace Webkul\BulkDeal\Helpers;

use Webkul\BulkDeal\Repositories\BulkDealRepository;
use Webkul\Checkout\Facades\Cart;

class BulkDeal
{
    public function __construct(protected BulkDealRepository $bulkDealRepository) {}

    public function applyToCart(): void
    {
        $cart = Cart::getCart();

        if (! $cart) {
            return;
        }

        $deals = $this->bulkDealRepository->getActiveDeals();

        if ($deals->isEmpty()) {
            return;
        }

        $totalBaseDiscount = 0.0;

        foreach ($deals as $deal) {
            $totalBaseDiscount += $this->computeDealDiscount($cart, $deal);
        }

        if ($totalBaseDiscount <= 0) {
            return;
        }

        $totalDiscount = core()->convertPrice($totalBaseDiscount);

        $cart->base_discount_amount = round($cart->base_discount_amount + $totalBaseDiscount, 2);
        $cart->discount_amount      = round($cart->discount_amount + $totalDiscount, 2);
        $cart->base_grand_total     = round(max(0, $cart->base_grand_total - $totalBaseDiscount), 2);
        $cart->grand_total          = round(max(0, $cart->grand_total - $totalDiscount), 2);

        $cart->save();
    }

    protected function computeDealDiscount($cart, $deal): float
    {
        $triggerQty = (int) $deal->paid_quantity + (int) $deal->deal_quantity;

        // Expand parent cart items into individual price units (skip child items)
        $units = [];

        foreach ($cart->items as $item) {
            $qty = (int) $item->quantity;

            for ($i = 0; $i < $qty; $i++) {
                $units[] = (float) $item->base_price;
            }
        }

        $totalQty = count($units);

        if ($totalQty < $triggerQty) {
            return 0.0;
        }

        // Sort descending — most expensive items pay full price
        rsort($units);

        // How many complete deal cycles fit in the cart?
        $cycles = (int) floor($totalQty / $triggerQty);

        $totalBaseDiscount = 0.0;

        for ($cycle = 0; $cycle < $cycles; $cycle++) {
            // Within each cycle, the first paid_quantity units are at full price,
            // the next deal_quantity units get the deal price
            $offset    = $cycle * $triggerQty + (int) $deal->paid_quantity;
            $dealUnits = array_slice($units, $offset, (int) $deal->deal_quantity);

            $regularTotal = array_sum($dealUnits);

            if ($regularTotal <= 0) {
                continue;
            }

            // deal_price is the total replacement cost for the deal_quantity units
            $discount = $regularTotal - (float) $deal->deal_price;

            if ($discount > 0) {
                $totalBaseDiscount += $discount;
            }
        }

        return $totalBaseDiscount;
    }
}
