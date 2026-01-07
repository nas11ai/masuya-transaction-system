<?php

namespace App\Repositories;

use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    public function __construct(Customer $model)
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

    public function hasTransactions(int $customerId): bool
    {
        $customer = $this->findOrFail($customerId);
        return $customer->hasTransactions();
    }

    public function getByCity(string $city): Collection
    {
        return $this->model
            ->where('city', $city)
            ->where('is_active', true)
            ->get();
    }

    public function getByProvince(string $province): Collection
    {
        return $this->model
            ->where('province', $province)
            ->where('is_active', true)
            ->get();
    }
}
