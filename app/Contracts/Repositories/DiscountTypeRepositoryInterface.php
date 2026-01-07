<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface DiscountTypeRepositoryInterface extends BaseRepositoryInterface
{
    public function findByCode(string $code);

    public function getActive(): Collection;

    public function getByType(string $type): Collection;
}
