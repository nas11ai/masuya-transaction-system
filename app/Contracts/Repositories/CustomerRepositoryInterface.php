<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CustomerRepositoryInterface extends BaseRepositoryInterface
{
    public function findByCode(string $code);

    public function search(string $keyword, int $perPage = 15): LengthAwarePaginator;

    public function getActive(int $perPage = 15): LengthAwarePaginator;

    public function hasTransactions(int $customerId): bool;

    public function getByCity(string $city): Collection;

    public function getByProvince(string $province): Collection;
}
