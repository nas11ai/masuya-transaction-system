<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface TransactionRepositoryInterface extends BaseRepositoryInterface
{
    public function findByInvoiceNo(string $invoiceNo);

    public function getByStatus(string $status, int $perPage = 15): LengthAwarePaginator;

    public function getByCustomer(int $customerId, int $perPage = 15): LengthAwarePaginator;

    public function getByDateRange(string $startDate, string $endDate, int $perPage = 15): LengthAwarePaginator;

    public function getByMonth(int $year, int $month): Collection;

    public function generateInvoiceNumber(string $date): string;

    public function getLastInvoiceNumber(int $year, int $month): ?string;

    public function getTotalByStatus(string $status): float;

    public function getTotalByDateRange(string $startDate, string $endDate): float;
}
