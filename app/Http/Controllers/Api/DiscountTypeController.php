<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiscountType\StoreDiscountTypeRequest;
use App\Http\Requests\DiscountType\UpdateDiscountTypeRequest;
use App\Http\Resources\DiscountTypeResource;
use App\Contracts\Repositories\DiscountTypeRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class DiscountTypeController extends Controller
{
    use ApiResponse;

    public function __construct(
        private DiscountTypeRepositoryInterface $discountTypeRepository
    ) {
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $discounts = $this->discountTypeRepository->paginate($perPage);

        return $this->paginatedResponse(
            DiscountTypeResource::collection($discounts)->resource,
            'Discount types retrieved successfully'
        );
    }

    public function active()
    {
        $discounts = $this->discountTypeRepository->getActive();

        return $this->successResponse(
            DiscountTypeResource::collection($discounts),
            'Active discount types retrieved successfully'
        );
    }

    public function store(StoreDiscountTypeRequest $request)
    {
        $discount = $this->discountTypeRepository->create($request->validated());

        return $this->successResponse(
            new DiscountTypeResource($discount),
            'Discount type created successfully',
            201
        );
    }

    public function show(int $id)
    {
        $discount = $this->discountTypeRepository->findOrFail($id);

        return $this->successResponse(
            new DiscountTypeResource($discount),
            'Discount type retrieved successfully'
        );
    }

    public function update(UpdateDiscountTypeRequest $request, int $id)
    {
        $this->discountTypeRepository->update($id, $request->validated());
        $discount = $this->discountTypeRepository->findOrFail($id);

        return $this->successResponse(
            new DiscountTypeResource($discount),
            'Discount type updated successfully'
        );
    }

    public function destroy(int $id)
    {
        $this->discountTypeRepository->delete($id);

        return $this->successResponse(
            null,
            'Discount type deleted successfully'
        );
    }
}
