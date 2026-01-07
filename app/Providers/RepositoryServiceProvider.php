<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Contracts
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\TransactionRepositoryInterface;
use App\Contracts\Repositories\DiscountTypeRepositoryInterface;
use App\Contracts\Repositories\StockMovementRepositoryInterface;

// Implementations
use App\Repositories\ProductRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\DiscountTypeRepository;
use App\Repositories\StockMovementRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(DiscountTypeRepositoryInterface::class, DiscountTypeRepository::class);
        $this->app->bind(StockMovementRepositoryInterface::class, StockMovementRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
