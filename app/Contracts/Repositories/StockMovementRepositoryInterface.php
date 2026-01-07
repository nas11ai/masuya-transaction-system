<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface StockMovementRepositoryInterface extends BaseRepositoryInterface
{
    public function getByProduct(int $productId, int $perPage = 15): LengthAwarePaginator;

    public function getByTransaction(int $transactionId): Collection;

    public function getByType(string $type, int $perPage = 15): LengthAwarePaginator;

    public function getByDateRange(string $startDate, string $endDate, int $perPage = 15): LengthAwarePaginator;

    public function createMovement(array $data): mixed;
}
