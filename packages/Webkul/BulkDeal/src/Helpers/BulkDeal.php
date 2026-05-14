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

        foreach ($deals as $deal) {
            $this->processDeal($cart, $deal);
        }
    }

    protected function processDeal($cart, $deal): void
    {
        $triggerQty = $deal->paid_quantity + $deal->deal_quantity;

        // Expand items into individual units with references back to cart item
        $units = [];

        foreach ($cart->items as $item) {
            $qty = (int) $item->quantity;

            for ($i = 0; $i < $qty; $i++) {
                $units[] = [
                    'item'       => $item,
                    'base_price' => (float) $item->base_price,
                    'price'      => (float) $item->price,
                ];
            }
        }

        $totalQty = count($units);

        if ($totalQty < $triggerQty) {
            return;
        }

        // Sort descending by price — most expensive first (charged at full price)
        usort($units, fn ($a, $b) => $b['base_price'] <=> $a['base_price']);

        // The first paid_quantity units get no discount
        // The next deal_quantity units get the deal price
        $dealUnits = array_slice($units, $deal->paid_quantity, $deal->deal_quantity);

        if (empty($dealUnits)) {
            return;
        }

        // Calculate regular total for deal units (base currency)
        $regularBaseTotal = array_sum(array_column($dealUnits, 'base_price'));

        if ($regularBaseTotal <= 0) {
            return;
        }

        // Positive = discount (deal is cheaper than original)
        // Negative = surcharge (deal forces a higher price than original)
        $baseDiscount = $regularBaseTotal - (float) $deal->deal_price;

        // Group deal units by cart item to apply discount in one pass
        $discountPerItem = [];

        foreach ($dealUnits as $unit) {
            $itemId = $unit['item']->id;
            $discountPerItem[$itemId] = ($discountPerItem[$itemId] ?? 0)
                + ($baseDiscount * ($unit['base_price'] / $regularBaseTotal));
        }

        foreach ($cart->items as $item) {
            if (! isset($discountPerItem[$item->id])) {
                continue;
            }

            $baseItemDiscount = $discountPerItem[$item->id];
            $itemDiscount     = core()->convertPrice($baseItemDiscount);

            // Allow negative discount_amount (acts as a surcharge when deal_price > original price)
            $item->base_discount_amount = (float) ($item->base_discount_amount ?? 0) + $baseItemDiscount;
            $item->discount_amount      = (float) ($item->discount_amount ?? 0) + $itemDiscount;

            $item->save();
        }
    }
}
