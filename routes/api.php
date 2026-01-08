<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DiscountTypeController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\StockMovementController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });

    // Products
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/', [ProductController::class, 'store']);
        Route::get('/low-stock', [ProductController::class, 'lowStock']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });

    // Customers
    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'index']);
        Route::post('/', [CustomerController::class, 'store']);
        Route::get('/{id}', [CustomerController::class, 'show']);
        Route::put('/{id}', [CustomerController::class, 'update']);
        Route::delete('/{id}', [CustomerController::class, 'destroy']);
    });

    // Discount Types
    Route::prefix('discount-types')->group(function () {
        Route::get('/', [DiscountTypeController::class, 'index']);
        Route::post('/', [DiscountTypeController::class, 'store']);
        Route::get('/active', [DiscountTypeController::class, 'active']);
        Route::get('/{id}', [DiscountTypeController::class, 'show']);
        Route::put('/{id}', [DiscountTypeController::class, 'update']);
        Route::delete('/{id}', [DiscountTypeController::class, 'destroy']);
    });

    // Transactions
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::post('/', [TransactionController::class, 'store']);
        Route::get('/invoice/{invoiceNo}', [TransactionController::class, 'showByInvoice']);
        Route::get('/{id}', [TransactionController::class, 'show']);
        Route::post('/{id}/complete', [TransactionController::class, 'complete']);
        Route::post('/{id}/cancel', [TransactionController::class, 'cancel']);
        Route::delete('/{id}', [TransactionController::class, 'destroy']);
    });

    // Stock Movements
    Route::prefix('stock-movements')->group(function () {
        Route::get('/', [StockMovementController::class, 'index']);
        Route::post('/add', [StockMovementController::class, 'addStock']);
        Route::post('/adjust', [StockMovementController::class, 'adjustStock']);
    });
});
