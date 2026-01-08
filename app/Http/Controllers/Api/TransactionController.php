<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Services\TransactionService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    use ApiResponse;

    public function __construct(
        private TransactionService $transactionService
    ) {
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $status = $request->get('status');

        if ($status) {
            $transactions = $this->transactionService->getTransactionsByStatus($status, $perPage);
        } else {
            $transactions = $this->transactionService->getAllTransactions($perPage);
        }

        return $this->paginatedResponse(
            TransactionResource::collection($transactions)->resource,
            'Transactions retrieved successfully'
        );
    }

    public function store(StoreTransactionRequest $request)
    {
        try {
            $transaction = $this->transactionService->createTransaction(
                $request->validated(),
                $request->user()->id
            );

            return $this->successResponse(
                new TransactionResource($transaction),
                'Transaction created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function show(int $id)
    {
        $transaction = $this->transactionService->getTransactionById($id);

        return $this->successResponse(
            new TransactionResource($transaction->load('details.discounts', 'customer', 'creator')),
            'Transaction retrieved successfully'
        );
    }

    public function showByInvoice(string $invoiceNo)
    {
        $transaction = $this->transactionService->getTransactionByInvoiceNo($invoiceNo);

        if (!$transaction) {
            return $this->errorResponse('Transaction not found', 404);
        }

        return $this->successResponse(
            new TransactionResource($transaction),
            'Transaction retrieved successfully'
        );
    }

    public function complete(int $id, Request $request)
    {
        try {
            $transaction = $this->transactionService->completeTransaction($id, $request->user()->id);

            return $this->successResponse(
                new TransactionResource($transaction),
                'Transaction completed successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function cancel(int $id)
    {
        try {
            $transaction = $this->transactionService->cancelTransaction($id);

            return $this->successResponse(
                new TransactionResource($transaction),
                'Transaction cancelled successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function destroy(int $id)
    {
        try {
            $transaction = $this->transactionService->getTransactionById($id);

            if (!$transaction->isDraft()) {
                return $this->errorResponse('Only draft transactions can be deleted', 400);
            }

            $transaction->delete();

            return $this->successResponse(
                null,
                'Transaction deleted successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
