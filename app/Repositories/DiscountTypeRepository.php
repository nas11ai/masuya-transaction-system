<?php

namespace App\Repositories;

use App\Contracts\Repositories\DiscountTypeRepositoryInterface;
use App\Models\DiscountType;
use Illuminate\Database\Eloquent\Collection;

class DiscountTypeRepository extends BaseRepository implements DiscountTypeRepositoryInterface
{
    public function __construct(DiscountType $model)
    {
        parent::__construct($model);
    }

    public function findByCode(string $code)
    {
        return $this->model->where('code', $code)->first();
    }

    public function getActive(): Collection
    {
        return $this->model->active()->get();
    }

    public function getByType(string $type): Collection
    {
        return $this->model
            ->where('type', $type)
            ->where('is_active', true)
            ->get();
    }
}
