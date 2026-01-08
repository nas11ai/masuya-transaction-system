<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'code' => 'LAPTOP001',
            'name' => 'Asus ROG Strix G15',
            'price' => 25000000,
            'stock' => 15,
            'description' => 'Gaming laptop with RTX 4060, 16GB RAM, 512GB SSD',
            'is_active' => true,
        ]);

        Product::create([
            'code' => 'MOUSE001',
            'name' => 'Logitech G502 Hero',
            'price' => 750000,
            'stock' => 50,
            'description' => 'Gaming mouse with 25K DPI sensor',
            'is_active' => true,
        ]);

        Product::create([
            'code' => 'KEYBOARD001',
            'name' => 'Corsair K70 RGB',
            'price' => 2500000,
            'stock' => 30,
            'description' => 'Mechanical gaming keyboard with Cherry MX switches',
            'is_active' => true,
        ]);

        Product::create([
            'code' => 'MONITOR001',
            'name' => 'LG UltraGear 27"',
            'price' => 5500000,
            'stock' => 8,
            'description' => '144Hz gaming monitor with 1ms response time',
            'is_active' => true,
        ]);

        Product::create([
            'code' => 'HEADSET001',
            'name' => 'Razer BlackShark V2',
            'price' => 1500000,
            'stock' => 25,
            'description' => 'Gaming headset with THX 7.1 surround sound',
            'is_active' => true,
        ]);

        Product::factory(45)->create();

        Product::factory(5)->lowStock()->create();

        Product::factory(3)->outOfStock()->create();

        Product::factory(2)->inactive()->create();

        $this->command->info('Products created successfully!');
    }
}
