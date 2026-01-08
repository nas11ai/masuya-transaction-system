<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockMovement\AdjustStockRequest;
use App\Http\Requests\StockMovement\AddStockRequest;
use App\Http\Resources\StockMovementResource;
use App\Services\StockService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    use ApiResponse;

    public function __construct(
        private StockService $stockService
    ) {
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);

        $filters = array_filter([
            'product_id' => $request->get('product_id'),
            'type' => $request->get('type'),
            'user_id' => $request->get('user_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ]);

        $movements = $this->stockService->getAllStockMovements($filters, $perPage);

        return $this->paginatedResponse(
            StockMovementResource::collection($movements)->resource,
            'Stock movements retrieved successfully'
        );
    }

    public function addStock(AddStockRequest $request)
    {
        try {
            $movement = $this->stockService->addStock(
                $request->product_id,
                $request->qty,
                $request->user()->id,
                $request->reference_no,
                $request->notes
            );

            return $this->successResponse(
                new StockMovementResource($movement),
                'Stock added successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function adjustStock(AdjustStockRequest $request)
    {
        try {
            $movement = $this->stockService->adjustStock(
                $request->product_id,
                $request->new_stock,
                $request->user()->id,
                $request->notes
            );

            return $this->successResponse(
                new StockMovementResource($movement),
                'Stock adjusted successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
