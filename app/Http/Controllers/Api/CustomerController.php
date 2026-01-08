<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Services\CustomerService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    use ApiResponse;

    public function __construct(
        private CustomerService $customerService
    ) {
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');

        if ($search) {
            $customers = $this->customerService->searchCustomers($search, $perPage);
        } else {
            $customers = $this->customerService->getAllCustomers($perPage);
        }

        return $this->paginatedResponse(
            CustomerResource::collection($customers)->resource,
            'Customers retrieved successfully'
        );
    }

    public function store(StoreCustomerRequest $request)
    {
        try {
            $customer = $this->customerService->createCustomer($request->validated());

            return $this->successResponse(
                new CustomerResource($customer),
                'Customer created successfully',
                201
            );
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    public function show(int $id)
    {
        $customer = $this->customerService->getCustomerById($id);

        return $this->successResponse(
            new CustomerResource($customer),
            'Customer retrieved successfully'
        );
    }

    public function update(UpdateCustomerRequest $request, int $id)
    {
        try {
            $customer = $this->customerService->updateCustomer($id, $request->validated());

            return $this->successResponse(
                new CustomerResource($customer),
                'Customer updated successfully'
            );
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->customerService->deleteCustomer($id);

            return $this->successResponse(
                null,
                'Customer deleted successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
