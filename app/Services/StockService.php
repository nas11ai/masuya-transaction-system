<?php

namespace App\Services;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\StockMovementRepositoryInterface;
use App\Enums\StockMovementType;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private StockMovementRepositoryInterface $stockMovementRepository
    ) {
    }

    public function getAllStockMovements(array $filters = [], int $perPage = 15)
    {
        return $this->stockMovementRepository
            ->paginateWithFilters($filters, $perPage);
    }

    public function addStock(int $productId, int $qty, int $userId, ?string $referenceNo = null, ?string $notes = null)
    {
        return DB::transaction(function () use ($productId, $qty, $userId, $referenceNo, $notes) {
            $product = $this->productRepository->findOrFail($productId);

            $stockBefore = $product->stock;
            $stockAfter = $stockBefore + $qty;

            $this->productRepository->updateStock($productId, $stockAfter);

            return $this->stockMovementRepository->createMovement([
                'product_id' => $productId,
                'user_id' => $userId,
                'type' => StockMovementType::IN,
                'qty' => $qty,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'reference_no' => $referenceNo,
                'notes' => $notes,
            ]);
        });
    }

    public function reduceStock(int $productId, int $qty, int $userId, ?int $transactionId = null, ?string $referenceNo = null, ?string $notes = null)
    {
        return DB::transaction(function () use ($productId, $qty, $userId, $transactionId, $referenceNo, $notes) {
            $product = $this->productRepository->findOrFail($productId);

            if ($product->stock < $qty) {
                throw new \Exception("Insufficient stock for product: {$product->name}. Available: {$product->stock}, Requested: {$qty}");
            }

            $stockBefore = $product->stock;
            $stockAfter = $stockBefore - $qty;

            $this->productRepository->updateStock($productId, $stockAfter);

            return $this->stockMovementRepository->createMovement([
                'product_id' => $productId,
                'transaction_id' => $transactionId,
                'user_id' => $userId,
                'type' => StockMovementType::OUT,
                'qty' => -$qty,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'reference_no' => $referenceNo,
                'notes' => $notes,
            ]);
        });
    }

    public function adjustStock(int $productId, int $newStock, int $userId, string $notes)
    {
        return DB::transaction(function () use ($productId, $newStock, $userId, $notes) {
            $product = $this->productRepository->findOrFail($productId);

            $stockBefore = $product->stock;
            $difference = $newStock - $stockBefore;

            $this->productRepository->updateStock($productId, $newStock);

            return $this->stockMovementRepository->createMovement([
                'product_id' => $productId,
                'user_id' => $userId,
                'type' => StockMovementType::ADJUSTMENT,
                'qty' => $difference,
                'stock_before' => $stockBefore,
                'stock_after' => $newStock,
                'notes' => $notes,
            ]);
        });
    }

    public function getStockHistory(int $productId, int $perPage = 15)
    {
        return $this->stockMovementRepository->getByProduct($productId, $perPage);
    }

    public function checkStock(int $productId, int $requiredQty): bool
    {
        $product = $this->productRepository->findOrFail($productId);
        return $product->stock >= $requiredQty;
    }
}
