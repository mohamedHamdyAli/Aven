<?php

namespace Webkul\DataTransfer\Helpers\Importers\Inventory;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Webkul\DataTransfer\Contracts\ImportBatch as ImportBatchContract;
use Webkul\DataTransfer\Helpers\Import;
use Webkul\DataTransfer\Helpers\Importers\AbstractImporter;
use Webkul\DataTransfer\Repositories\ImportBatchRepository;
use Webkul\Product\Jobs\UpdateCreateInventoryIndex as UpdateCreateInventoryIndexJob;

class Importer extends AbstractImporter
{
    const ERROR_SKU_NOT_FOUND = 'sku_not_found';

    const ERROR_INVALID_SOURCE = 'invalid_source_code';

    const ERROR_DUPLICATE_ROW = 'duplicate_sku_source';

    protected array $validColumnNames = [
        'sku',
        'source_code',
        'qty',
    ];

    protected array $messages = [
        self::ERROR_SKU_NOT_FOUND => 'data_transfer::app.importers.inventory-adjustments.validation.errors.sku-not-found',
        self::ERROR_INVALID_SOURCE => 'data_transfer::app.importers.inventory-adjustments.validation.errors.invalid-source-code',
        self::ERROR_DUPLICATE_ROW => 'data_transfer::app.importers.inventory-adjustments.validation.errors.duplicate-sku-source',
    ];

    protected $permanentAttributes = ['sku'];

    protected string $masterAttributeCode = 'sku';

    /**
     * Track seen sku+source pairs per validation pass to catch duplicates
     */
    protected array $seenRows = [];

    /**
     * Collected product IDs to reindex after import
     */
    protected array $affectedProductIds = [];

    public function __construct(
        protected ImportBatchRepository $importBatchRepository,
        protected SKUStorage $skuStorage
    ) {
        parent::__construct($importBatchRepository);
    }

    protected function initErrorMessages(): void
    {
        foreach ($this->messages as $errorCode => $message) {
            $this->errorHelper->addErrorMessage($errorCode, trans($message));
        }

        parent::initErrorMessages();
    }

    public function validateData(): void
    {
        $this->skuStorage->init();

        parent::validateData();
    }

    public function validateRow(array $rowData, int $rowNumber): bool
    {
        if (isset($this->validatedRows[$rowNumber])) {
            return ! $this->errorHelper->isRowInvalid($rowNumber);
        }

        $this->validatedRows[$rowNumber] = true;

        $validator = Validator::make($rowData, [
            'sku'         => 'required|string',
            'source_code' => 'required|string',
            'qty'         => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            $failedAttributes = $validator->failed();

            foreach ($validator->errors()->getMessages() as $attributeCode => $message) {
                $errorCode = array_key_first($failedAttributes[$attributeCode] ?? []);

                $this->skipRow($rowNumber, $errorCode, $attributeCode, current($message));
            }

            return false;
        }

        /**
         * Load SKU into storage if not already loaded
         */
        if (! $this->skuStorage->hasSku($rowData['sku'])) {
            $this->skuStorage->load([$rowData['sku']]);
        }

        if (! $this->skuStorage->hasSku($rowData['sku'])) {
            $this->skipRow($rowNumber, self::ERROR_SKU_NOT_FOUND, 'sku');

            return false;
        }

        if (! $this->skuStorage->hasSource($rowData['source_code'])) {
            $this->skipRow($rowNumber, self::ERROR_INVALID_SOURCE, 'source_code');

            return false;
        }

        $rowKey = $rowData['sku'].'|'.$rowData['source_code'];

        if (in_array($rowKey, $this->seenRows)) {
            $this->skipRow($rowNumber, self::ERROR_DUPLICATE_ROW, 'sku');

            return false;
        }

        $this->seenRows[] = $rowKey;

        return true;
    }

    public function importBatch(ImportBatchContract $batch): bool
    {
        Event::dispatch('data_transfer.imports.batch.import.before', $batch);

        if ($batch->import->action == Import::ACTION_DELETE) {
            $this->resetInventories($batch);
        } else {
            $this->saveInventories($batch);
        }

        $batch = $this->importBatchRepository->update([
            'state' => Import::STATE_PROCESSED,

            'summary' => [
                'created' => $this->getCreatedItemsCount(),
                'updated' => $this->getUpdatedItemsCount(),
                'deleted' => $this->getDeletedItemsCount(),
            ],
        ], $batch->id);

        Event::dispatch('data_transfer.imports.batch.import.after', $batch);

        return true;
    }

    /**
     * Set qty from each row in the batch
     */
    protected function saveInventories(ImportBatchContract $batch): void
    {
        $skus = Arr::pluck($batch->data, 'sku');

        $this->skuStorage->load($skus);

        foreach ($batch->data as $rowData) {
            $productId  = $this->skuStorage->getProductId($rowData['sku']);
            $sourceId   = $this->skuStorage->getSourceId($rowData['source_code']);

            if (! $productId || ! $sourceId) {
                continue;
            }

            $existing = DB::table('product_inventories')
                ->where('product_id', $productId)
                ->where('inventory_source_id', $sourceId)
                ->where('vendor_id', 0)
                ->first();

            if ($existing) {
                DB::table('product_inventories')
                    ->where('id', $existing->id)
                    ->update(['qty' => (int) $rowData['qty']]);

                $this->updatedItemsCount++;
            } else {
                DB::table('product_inventories')->insert([
                    'product_id'           => $productId,
                    'inventory_source_id'  => $sourceId,
                    'vendor_id'            => 0,
                    'qty'                  => (int) $rowData['qty'],
                ]);

                $this->createdItemsCount++;
            }

            $this->affectedProductIds[] = $productId;
        }

        if (! empty($this->affectedProductIds)) {
            UpdateCreateInventoryIndexJob::dispatch(array_unique($this->affectedProductIds));
        }
    }

    /**
     * Reset qty to 0 for each row in the batch
     */
    protected function resetInventories(ImportBatchContract $batch): void
    {
        $skus = Arr::pluck($batch->data, 'sku');

        $this->skuStorage->load($skus);

        foreach ($batch->data as $rowData) {
            $productId = $this->skuStorage->getProductId($rowData['sku']);
            $sourceId  = $this->skuStorage->getSourceId($rowData['source_code']);

            if (! $productId || ! $sourceId) {
                continue;
            }

            DB::table('product_inventories')
                ->where('product_id', $productId)
                ->where('inventory_source_id', $sourceId)
                ->where('vendor_id', 0)
                ->update(['qty' => 0]);

            $this->deletedItemsCount++;

            $this->affectedProductIds[] = $productId;
        }

        if (! empty($this->affectedProductIds)) {
            UpdateCreateInventoryIndexJob::dispatch(array_unique($this->affectedProductIds));
        }
    }
}
