<?php

namespace Database\Seeders;

use App\Models\StockMovement;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class StockMovementSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $users = User::where('is_active', true)->get();

        if ($products->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Please seed products and users first!');
            return;
        }

        foreach ($products as $product) {
            $user = $users->random();
            $date = now()->subDays(rand(30, 90));

            StockMovement::create([
                'product_id' => $product->id,
                'transaction_id' => null,
                'user_id' => $user->id,
                'type' => 'in',
                'qty' => $product->stock,
                'stock_before' => 0,
                'stock_after' => $product->stock,
                'reference_no' => 'PO-INITIAL-' . $product->code,
                'notes' => 'Initial stock',
                'created_at' => $date,
            ]);
        }

        for ($i = 0; $i < 20; $i++) {
            $product = $products->random();
            $user = $users->random();
            $date = now()->subDays(rand(1, 60));

            $stockBefore = $product->stock;
            $adjustment = rand(-10, 20);
            $stockAfter = max(0, $stockBefore + $adjustment);

            StockMovement::create([
                'product_id' => $product->id,
                'transaction_id' => null,
                'user_id' => $user->id,
                'type' => 'adjustment',
                'qty' => $adjustment,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'reference_no' => 'ADJ-' . now()->format('Ymd') . '-' . rand(1000, 9999),
                'notes' => fake()->sentence(),
                'created_at' => $date,
            ]);

            $product->update(['stock' => $stockAfter]);
        }

        $this->command->info('Stock movements created successfully!');
    }
}
