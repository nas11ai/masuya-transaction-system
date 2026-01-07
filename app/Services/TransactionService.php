<?php

namespace App\Services;

use App\Contracts\Repositories\TransactionRepositoryInterface;
use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Enums\TransactionStatus;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\TransactionDetailDiscount;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function __construct(
        private TransactionRepositoryInterface $transactionRepository,
        private CustomerRepositoryInterface $customerRepository,
        private ProductRepositoryInterface $productRepository,
        private DiscountService $discountService,
        private StockService $stockService
    ) {
    }

    public function getAllTransactions(int $perPage = 15)
    {
        return $this->transactionRepository->paginate($perPage);
    }

    public function getTransactionsByStatus(string $status, int $perPage = 15)
    {
        return $this->transactionRepository->getByStatus($status, $perPage);
    }

    public function getTransactionById(int $id)
    {
        return $this->transactionRepository->findOrFail($id);
    }

    public function getTransactionByInvoiceNo(string $invoiceNo)
    {
        return $this->transactionRepository->findByInvoiceNo($invoiceNo);
    }

    public function createTransaction(array $data, int $userId)
    {
        return DB::transaction(function () use ($data, $userId) {
            $customer = $this->customerRepository->findOrFail($data['customer_id']);

            $invoiceNo = $this->transactionRepository->generateInvoiceNumber(
                $data['invoice_date'] ?? now()->toDateString()
            );

            $transaction = $this->transactionRepository->create([
                'customer_id' => $customer->id,
                'created_by' => $userId,
                'invoice_no' => $invoiceNo,
                'invoice_date' => $data['invoice_date'] ?? now()->toDateString(),
                'customer_code' => $customer->code,
                'customer_name' => $customer->name,
                'customer_address' => $customer->getFullAddress(),
                'notes' => $data['notes'] ?? null,
                'status' => TransactionStatus::DRAFT,
                'subtotal' => 0,
                'discount_total' => 0,
                'total' => 0,
            ]);

            $totalSubtotal = 0;
            $totalDiscount = 0;

            foreach ($data['items'] as $item) {
                $detail = $this->addTransactionDetail($transaction, $item);
                $totalSubtotal += $detail->subtotal;
                $totalDiscount += $detail->discount_amount;
            }

            $transaction->update([
                'subtotal' => $totalSubtotal,
                'discount_total' => $totalDiscount,
                'total' => $totalSubtotal - $totalDiscount,
            ]);

            return $transaction->fresh(['details.discounts', 'customer', 'creator']);
        });
    }

    private function addTransactionDetail(Transaction $transaction, array $itemData)
    {
        $product = $this->productRepository->findOrFail($itemData['product_id']);

        if (!$this->stockService->checkStock($product->id, $itemData['qty'])) {
            throw new \Exception("Insufficient stock for product: {$product->name}");
        }

        $price = $itemData['price'] ?? $product->price;

        $discountAmount = 0;
        $netPrice = $price;

        if (!empty($itemData['discounts'])) {
            $discountResult = $this->discountService->calculateCascadingDiscounts(
                $price,
                $itemData['discounts']
            );

            $discountAmount = $discountResult['total_discount'];
            $netPrice = $discountResult['net_price'];
        }

        $detail = TransactionDetail::create([
            'transaction_id' => $transaction->id,
            'product_id' => $product->id,
            'product_code' => $product->code,
            'product_name' => $product->name,
            'qty' => $itemData['qty'],
            'price' => $price,
            'discount_amount' => $discountAmount,
            'net_price' => $netPrice,
            'subtotal' => $netPrice * $itemData['qty'],
        ]);

        if (!empty($itemData['discounts'])) {
            $discountResult = $this->discountService->calculateCascadingDiscounts(
                $price,
                $itemData['discounts']
            );

            foreach ($discountResult['breakdown'] as $discount) {
                TransactionDetailDiscount::create([
                    'transaction_detail_id' => $detail->id,
                    'discount_type_id' => $itemData['discounts'][$discount['sequence'] - 1]['discount_type_id'] ?? null,
                    'sequence' => $discount['sequence'],
                    'discount_name' => $itemData['discounts'][$discount['sequence'] - 1]['name'] ?? "Discount {$discount['sequence']}",
                    'discount_type' => $discount['type'],
                    'discount_value' => $discount['value'],
                    'discount_amount' => $discount['amount'],
                ]);
            }
        }

        return $detail;
    }

    public function completeTransaction(int $id, int $userId)
    {
        return DB::transaction(function () use ($id, $userId) {
            $transaction = $this->transactionRepository->findOrFail($id);

            if ($transaction->status !== TransactionStatus::DRAFT) {
                throw new \Exception('Only draft transactions can be completed');
            }

            foreach ($transaction->details as $detail) {
                $this->stockService->reduceStock(
                    $detail->product_id,
                    $detail->qty,
                    $userId,
                    $transaction->id,
                    $transaction->invoice_no,
                    "Transaction completed: {$transaction->invoice_no}"
                );
            }

            $transaction->update(['status' => TransactionStatus::COMPLETED]);

            return $transaction->fresh(['details', 'stockMovements']);
        });
    }

    public function cancelTransaction(int $id)
    {
        return DB::transaction(function () use ($id) {
            $transaction = $this->transactionRepository->findOrFail($id);

            if ($transaction->status === TransactionStatus::CANCELLED) {
                throw new \Exception('Transaction is already cancelled');
            }

            $transaction->update(['status' => TransactionStatus::CANCELLED]);

            return $transaction;
        });
    }
}
