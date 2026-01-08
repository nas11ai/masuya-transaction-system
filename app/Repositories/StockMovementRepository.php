<?php

namespace App\Repositories;

use App\Contracts\Repositories\StockMovementRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class StockMovementRepository extends BaseRepository implements StockMovementRepositoryInterface
{
    public function __construct(StockMovement $model)
    {
        parent::__construct($model);
    }

    public function getByProduct(int $productId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->byProduct($productId)
            ->with(['user', 'transaction'])
            ->latest('created_at')
            ->paginate($perPage);
    }

    public function getByTransaction(int $transactionId): Collection
    {
        return $this->model
            ->where('transaction_id', $transactionId)
            ->with(['product', 'user'])
            ->get();
    }

    public function getByType(string $type, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->where('type', $type)
            ->with(['product', 'user'])
            ->latest('created_at')
            ->paginate($perPage);
    }

    public function getByDateRange(string $startDate, string $endDate, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->byDateRange($startDate, $endDate)
            ->with(['product', 'user'])
            ->latest('created_at')
            ->paginate($perPage);
    }

    public function createMovement(array $data): mixed
    {
        $data['created_at'] = now();
        return $this->model->create($data);
    }
    public function paginateWithFilters(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model
            ->with(['product', 'user', 'transaction'])
            ->latest('created_at');

        if (!empty($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('created_at', [
                $filters['start_date'],
                $filters['end_date']
            ]);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        return $query->paginate($perPage);
    }
}
