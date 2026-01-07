<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function findByCode(string $code)
    {
        return $this->model->where('code', $code)->first();
    }

    public function search(string $keyword, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->search($keyword)
            ->paginate($perPage);
    }

    public function getActive(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->active()
            ->paginate($perPage);
    }

    public function hasTransactions(int $productId): bool
    {
        $product = $this->findOrFail($productId);
        return $product->hasTransactions();
    }

    public function updateStock(int $productId, int $quantity): bool
    {
        return $this->model
            ->where('id', $productId)
            ->update(['stock' => $quantity]);
    }

    public function getLowStock(int $threshold = 10): Collection
    {
        return $this->model
            ->where('stock', '<=', $threshold)
            ->where('is_active', true)
            ->get();
    }
}
