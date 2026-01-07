<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function findByCode(string $code);

    public function search(string $keyword, int $perPage = 15): LengthAwarePaginator;

    public function getActive(int $perPage = 15): LengthAwarePaginator;

    public function hasTransactions(int $productId): bool;

    public function updateStock(int $productId, int $quantity): bool;

    public function getLowStock(int $threshold = 10): Collection;
}
