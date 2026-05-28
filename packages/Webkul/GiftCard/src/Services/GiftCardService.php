<?php

namespace Webkul\GiftCard\Services;

use Illuminate\Support\Facades\DB;
use Webkul\GiftCard\Models\GiftCard;

class GiftCardService
{
    public function validate(string $code): ?GiftCard
    {
        $card = GiftCard::where('code', strtoupper(trim($code)))->first();

        if (! $card || ! $card->isUsable()) {
            return null;
        }

        return $card;
    }

    public function applyToCart(int $cartId, string $code): array
    {
        $card = $this->validate($code);

        if (! $card) {
            return ['success' => false, 'message' => 'Invalid or expired gift card code.'];
        }

        $grandTotal = DB::table('cart')->where('id', $cartId)->value('grand_total') ?? 0;
        $discount   = min($card->remaining_balance, (float) $grandTotal);

        DB::table('cart')->where('id', $cartId)->update([
            'gift_card_code'     => strtoupper(trim($code)),
            'gift_card_discount' => $discount,
        ]);

        return [
            'success'  => true,
            'discount' => $discount,
            'message'  => 'Gift card applied! '.core()->formatPrice($discount).' discount.',
        ];
    }

    public function removeFromCart(int $cartId): void
    {
        DB::table('cart')->where('id', $cartId)->update([
            'gift_card_code'     => null,
            'gift_card_discount' => 0,
        ]);
    }

    public function redeemForOrder(string $code, float $amount): void
    {
        GiftCard::where('code', strtoupper($code))->increment('used_amount', $amount);

        $card = GiftCard::where('code', strtoupper($code))->first();
        if ($card && $card->remaining_balance <= 0) {
            $card->update(['is_active' => false]);
        }
    }
}
