<?php

namespace Webkul\DataTransfer\Helpers\Importers\Inventory;

use Webkul\Inventory\Repositories\InventorySourceRepository;
use Webkul\Product\Repositories\ProductRepository;

class SKUStorage
{
    /**
     * Items contains SKU as key and product_id as value
     */
    protected array $products = [];

    /**
     * Items contains source_code as key and source_id as value
     */
    protected array $sources = [];

    /**
     * Create a new helper instance.
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected InventorySourceRepository $inventorySourceRepository
    ) {}

    /**
     * Initialize storage
     */
    public function init(): void
    {
        $this->products = [];
        $this->sources = [];

        foreach ($this->inventorySourceRepository->all(['id', 'code']) as $source) {
            $this->sources[$source->code] = $source->id;
        }
    }

    /**
     * Load products by SKU list
     */
    public function load(array $skus): void
    {
        $products = $this->productRepository->findWhereIn('sku', $skus, ['id', 'sku']);

        foreach ($products as $product) {
            $this->products[$product->sku] = $product->id;
        }
    }

    /**
     * Check if SKU exists
     */
    public function hasSku(string $sku): bool
    {
        return isset($this->products[$sku]);
    }

    /**
     * Get product_id for a SKU
     */
    public function getProductId(string $sku): ?int
    {
        return $this->products[$sku] ?? null;
    }

    /**
     * Check if inventory source code exists
     */
    public function hasSource(string $code): bool
    {
        return isset($this->sources[$code]);
    }

    /**
     * Get source_id for a source code
     */
    public function getSourceId(string $code): ?int
    {
        return $this->sources[$code] ?? null;
    }

    /**
     * Get all source codes
     */
    public function getAllSources(): array
    {
        return $this->sources;
    }
}
