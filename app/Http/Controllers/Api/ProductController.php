<?php
// app/Http/Controllers/Api/ProductController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponse;

    public function __construct(
        private ProductService $productService
    ) {
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');

        if ($search) {
            $products = $this->productService->searchProducts($search, $perPage);
        } else {
            $products = $this->productService->getAllProducts($perPage);
        }

        return $this->paginatedResponse(
            ProductResource::collection($products)->resource,
            'Products retrieved successfully'
        );
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $product = $this->productService->createProduct($request->validated());

            return $this->successResponse(
                new ProductResource($product),
                'Product created successfully',
                201
            );
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    public function show(int $id)
    {
        $product = $this->productService->getProductById($id);

        return $this->successResponse(
            new ProductResource($product),
            'Product retrieved successfully'
        );
    }

    public function update(UpdateProductRequest $request, int $id)
    {
        try {
            $product = $this->productService->updateProduct($id, $request->validated());

            return $this->successResponse(
                new ProductResource($product),
                'Product updated successfully'
            );
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->productService->deleteProduct($id);

            return $this->successResponse(
                null,
                'Product deleted successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function lowStock(Request $request)
    {
        $threshold = $request->get('threshold', 10);
        $products = $this->productService->getLowStockProducts($threshold);

        return $this->successResponse(
            ProductResource::collection($products),
            'Low stock products retrieved successfully'
        );
    }
}
