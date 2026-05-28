<?php

namespace Webkul\FlashSale\Services;

use Illuminate\Support\Facades\DB;
use Webkul\FlashSale\Models\FlashSale;

class FlashSaleService
{
    private static ?array $activeForProductCache = null;

    public function apply(FlashSale $sale): void
    {
        $productIds = $sale->products()->pluck('products.id');

        if ($productIds->isEmpty()) {
            return;
        }

        $startsDate = $sale->starts_at->toDateString();
        $endsDate   = $sale->ends_at->toDateString();

        DB::table('product_flat')
            ->whereIn('product_id', $productIds)
            ->update([
                'special_price_from'  => $startsDate,
                'special_price_to'    => $endsDate,
                'flash_sale_ends_at'  => $sale->ends_at,
                // The special_price is set per-product in applyToProduct()
            ]);

        foreach ($productIds as $productId) {
            $price = DB::table('product_flat')
                ->where('product_id', $productId)
                ->whereNotNull('price')
                ->value('price');

            if ($price === null) {
                continue;
            }

            $discountedPrice = round($price * (1 - $sale->discount_percent / 100), 4);

            DB::table('product_flat')
                ->where('product_id', $productId)
                ->update(['special_price' => $discountedPrice]);
        }
    }

    public function expire(FlashSale $sale): void
    {
        $productIds = $sale->products()->pluck('products.id');

        DB::table('product_flat')
            ->whereIn('product_id', $productIds)
            ->where('flash_sale_ends_at', $sale->ends_at)
            ->update([
                'special_price'      => null,
                'special_price_from' => null,
                'special_price_to'   => null,
                'flash_sale_ends_at' => null,
            ]);
    }

    public function syncExpired(): int
    {
        $expired = FlashSale::where('active', true)
            ->where('ends_at', '<', now())
            ->get();

        foreach ($expired as $sale) {
            $this->expire($sale);
            $sale->update(['active' => false]);
        }

        return $expired->count();
    }

    public function syncActivatable(): int
    {
        $toActivate = FlashSale::where('active', false)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>', now())
            ->get();

        foreach ($toActivate as $sale) {
            $this->apply($sale);
            $sale->update(['active' => true]);
        }

        return $toActivate->count();
    }

    public function getActiveForProduct(int $productId): ?FlashSale
    {
        if (static::$activeForProductCache === null) {
            $sales = FlashSale::where('active', true)
                ->where('starts_at', '<=', now())
                ->where('ends_at', '>', now())
                ->orderBy('ends_at')
                ->with('products:id')
                ->get();

            static::$activeForProductCache = [];

            foreach ($sales as $sale) {
                foreach ($sale->products as $product) {
                    if (! isset(static::$activeForProductCache[$product->id])) {
                        static::$activeForProductCache[$product->id] = $sale;
                    }
                }
            }
        }

        return static::$activeForProductCache[$productId] ?? null;
    }
}
