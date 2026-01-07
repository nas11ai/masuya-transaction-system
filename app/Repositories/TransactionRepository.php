<?php

namespace App\Repositories;

use App\Contracts\Repositories\TransactionRepositoryInterface;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }

    public function findByInvoiceNo(string $invoiceNo)
    {
        return $this->model
            ->where('invoice_no', $invoiceNo)
            ->with(['details.discounts', 'customer', 'creator'])
            ->first();
    }

    public function getByStatus(string $status, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->where('status', $status)
            ->with(['customer', 'creator'])
            ->latest('invoice_date')
            ->paginate($perPage);
    }

    public function getByCustomer(int $customerId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->where('customer_id', $customerId)
            ->with(['details', 'creator'])
            ->latest('invoice_date')
            ->paginate($perPage);
    }

    public function getByDateRange(string $startDate, string $endDate, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->byDateRange($startDate, $endDate)
            ->with(['customer', 'creator'])
            ->latest('invoice_date')
            ->paginate($perPage);
    }

    public function getByMonth(int $year, int $month): Collection
    {
        return $this->model
            ->byMonth($year, $month)
            ->with(['details', 'customer'])
            ->get();
    }

    public function generateInvoiceNumber(string $date): string
    {
        $parsedDate = \Carbon\Carbon::parse($date);
        $yearMonth = $parsedDate->format('ym'); // Format: 2501 for Jan 2025

        $lastInvoice = $this->getLastInvoiceNumber(
            $parsedDate->year,
            $parsedDate->month
        );

        if ($lastInvoice) {
            // Extract sequence from last invoice (INV/2501/0001 -> 0001)
            $lastSequence = (int) substr($lastInvoice, -4);
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }

        return sprintf('INV/%s/%04d', $yearMonth, $newSequence);
    }

    public function getLastInvoiceNumber(int $year, int $month): ?string
    {
        return $this->model
            ->whereYear('invoice_date', $year)
            ->whereMonth('invoice_date', $month)
            ->orderBy('invoice_no', 'desc')
            ->value('invoice_no');
    }

    public function getTotalByStatus(string $status): float
    {
        return $this->model
            ->where('status', $status)
            ->sum('total');
    }

    public function getTotalByDateRange(string $startDate, string $endDate): float
    {
        return $this->model
            ->byDateRange($startDate, $endDate)
            ->where('status', 'completed')
            ->sum('total');
    }
}
