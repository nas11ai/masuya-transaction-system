<?php
// database/seeders/TransactionSeeder.php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\TransactionDetailDiscount;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use App\Models\DiscountType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::where('is_active', true)->get();
        $products = Product::where('is_active', true)->where('stock', '>', 0)->get();
        $users = User::where('is_active', true)->get();
        $discountTypes = DiscountType::where('is_active', true)->get();

        if ($customers->isEmpty() || $products->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Please seed customers, products, and users first!');
            return;
        }

        for ($i = 0; $i < 50; $i++) {
            $customer = $customers->random();
            $user = $users->random();

            $date = now()->subDays(rand(0, 90));

            $transaction = Transaction::create([
                'customer_id' => $customer->id,
                'created_by' => $user->id,
                'invoice_no' => $this->generateInvoiceNumber($date),
                'invoice_date' => $date->toDateString(),
                'customer_code' => $customer->code,
                'customer_name' => $customer->name,
                'customer_address' => $customer->getFullAddress(),
                'notes' => fake()->optional()->sentence(),
                'status' => fake()->randomElement(['draft', 'completed', 'completed', 'completed', 'cancelled']), // 60% completed
                'subtotal' => 0,
                'discount_total' => 0,
                'total' => 0,
            ]);

            $itemCount = rand(1, 5);
            $totalSubtotal = 0;
            $totalDiscount = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->random();
                $qty = rand(1, 3);
                $price = $product->price;

                $discountCount = rand(0, 3);
                $discountAmount = 0;
                $currentPrice = $price;

                $detail = TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'product_code' => $product->code,
                    'product_name' => $product->name,
                    'qty' => $qty,
                    'price' => $price,
                    'discount_amount' => 0, // will update later
                    'net_price' => 0, // will update later
                    'subtotal' => 0, // will update later
                ]);

                // Apply cascading discounts
                for ($k = 0; $k < $discountCount; $k++) {
                    $discount = $discountTypes->random();
                    $discValue = 0;

                    if ($discount->type === 'percentage') {
                        $discValue = round($currentPrice * ($discount->value / 100), 2);
                    } else {
                        $discValue = min($discount->value, $currentPrice);
                    }

                    TransactionDetailDiscount::create([
                        'transaction_detail_id' => $detail->id,
                        'discount_type_id' => $discount->id,
                        'sequence' => $k + 1,
                        'discount_name' => $discount->name,
                        'discount_type' => $discount->type,
                        'discount_value' => $discount->value,
                        'discount_amount' => $discValue,
                    ]);

                    $discountAmount += $discValue;
                    $currentPrice -= $discValue;
                }

                $netPrice = $price - $discountAmount;
                $subtotal = $netPrice * $qty;

                $detail->update([
                    'discount_amount' => $discountAmount,
                    'net_price' => $netPrice,
                    'subtotal' => $subtotal,
                ]);

                $totalSubtotal += $subtotal;
                $totalDiscount += ($discountAmount * $qty);
            }

            $transaction->update([
                'subtotal' => $totalSubtotal,
                'discount_total' => $totalDiscount,
                'total' => $totalSubtotal,
            ]);
        }

        $this->command->info('Transactions created successfully!');
    }

    private function generateInvoiceNumber($date)
    {
        $yearMonth = $date->format('ym');

        $lastInvoice = Transaction::whereYear('invoice_date', $date->year)
            ->whereMonth('invoice_date', $date->month)
            ->orderBy('invoice_no', 'desc')
            ->value('invoice_no');

        if ($lastInvoice) {
            $lastSequence = (int) substr($lastInvoice, -4);
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }

        return sprintf('INV/%s/%04d', $yearMonth, $newSequence);
    }
}
