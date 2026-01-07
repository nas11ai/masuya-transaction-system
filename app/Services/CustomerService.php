<?php

namespace App\Services;

use App\Contracts\Repositories\CustomerRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CustomerService
{
    public function __construct(
        private CustomerRepositoryInterface $customerRepository
    ) {
    }

    public function getAllCustomers(int $perPage = 15)
    {
        return $this->customerRepository->paginate($perPage);
    }

    public function getActiveCustomers(int $perPage = 15)
    {
        return $this->customerRepository->getActive($perPage);
    }

    public function searchCustomers(string $keyword, int $perPage = 15)
    {
        return $this->customerRepository->search($keyword, $perPage);
    }

    public function getCustomerById(int $id)
    {
        return $this->customerRepository->findOrFail($id);
    }

    public function getCustomerByCode(string $code)
    {
        return $this->customerRepository->findByCode($code);
    }

    public function createCustomer(array $data)
    {
        if (!preg_match('/^[a-zA-Z0-9]+$/', $data['code'])) {
            throw new \InvalidArgumentException('Customer code must be alphanumeric only');
        }

        if ($this->customerRepository->findByCode($data['code'])) {
            throw new \InvalidArgumentException('Customer code already exists');
        }

        return DB::transaction(function () use ($data) {
            return $this->customerRepository->create($data);
        });
    }

    public function updateCustomer(int $id, array $data)
    {
        $this->customerRepository->findOrFail($id);

        if (isset($data['code'])) {
            if (!preg_match('/^[a-zA-Z0-9]+$/', $data['code'])) {
                throw new \InvalidArgumentException('Customer code must be alphanumeric only');
            }

            $existingCustomer = $this->customerRepository->findByCode($data['code']);
            if ($existingCustomer && $existingCustomer->id !== $id) {
                throw new \InvalidArgumentException('Customer code already exists');
            }
        }

        return DB::transaction(function () use ($id, $data) {
            $this->customerRepository->update($id, $data);
            return $this->customerRepository->findOrFail($id);
        });
    }

    public function deleteCustomer(int $id)
    {
        if ($this->customerRepository->hasTransactions($id)) {
            throw new \Exception('Cannot delete customer that has transactions');
        }

        return $this->customerRepository->delete($id);
    }

    public function getCustomersByCity(string $city)
    {
        return $this->customerRepository->getByCity($city);
    }

    public function getCustomersByProvince(string $province)
    {
        return $this->customerRepository->getByProvince($province);
    }
}
