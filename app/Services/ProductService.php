<?php

namespace App\Services;

use App\Contracts\Repositories\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {
    }

    public function getAllProducts(int $perPage = 15)
    {
        return $this->productRepository->paginate($perPage);
    }

    public function getActiveProducts(int $perPage = 15)
    {
        return $this->productRepository->getActive($perPage);
    }

    public function searchProducts(string $keyword, int $perPage = 15)
    {
        return $this->productRepository->search($keyword, $perPage);
    }

    public function getProductById(int $id)
    {
        return $this->productRepository->findOrFail($id);
    }

    public function getProductByCode(string $code)
    {
        return $this->productRepository->findByCode($code);
    }

    public function createProduct(array $data)
    {
        if (!preg_match('/^[a-zA-Z0-9]+$/', $data['code'])) {
            throw new \InvalidArgumentException('Product code must be alphanumeric only');
        }

        if ($this->productRepository->findByCode($data['code'])) {
            throw new \InvalidArgumentException('Product code already exists');
        }

        return DB::transaction(function () use ($data) {
            return $this->productRepository->create($data);
        });
    }

    public function updateProduct(int $id, array $data)
    {
        $this->productRepository->findOrFail($id);

        if (isset($data['code'])) {
            if (!preg_match('/^[a-zA-Z0-9]+$/', $data['code'])) {
                throw new \InvalidArgumentException('Product code must be alphanumeric only');
            }

            $existingProduct = $this->productRepository->findByCode($data['code']);
            if ($existingProduct && $existingProduct->id !== $id) {
                throw new \InvalidArgumentException('Product code already exists');
            }
        }

        return DB::transaction(function () use ($id, $data) {
            $this->productRepository->update($id, $data);
            return $this->productRepository->findOrFail($id);
        });
    }

    public function deleteProduct(int $id)
    {
        if ($this->productRepository->hasTransactions($id)) {
            throw new \Exception('Cannot delete product that has transactions');
        }

        return $this->productRepository->delete($id);
    }

    public function getLowStockProducts(int $threshold = 10)
    {
        return $this->productRepository->getLowStock($threshold);
    }
}
