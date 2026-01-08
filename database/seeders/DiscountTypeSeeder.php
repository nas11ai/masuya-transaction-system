<?php

namespace Database\Seeders;

use App\Models\DiscountType;
use Illuminate\Database\Seeder;

class DiscountTypeSeeder extends Seeder
{
    public function run(): void
    {
        DiscountType::create([
            'code' => 'MEMBER10',
            'name' => 'Member Discount 10%',
            'type' => 'percentage',
            'value' => 10,
            'description' => 'Standard member discount',
            'is_active' => true,
        ]);

        DiscountType::create([
            'code' => 'MEMBER15',
            'name' => 'Member Discount 15%',
            'type' => 'percentage',
            'value' => 15,
            'description' => 'Premium member discount',
            'is_active' => true,
        ]);

        DiscountType::create([
            'code' => 'PROMO5',
            'name' => 'Promo Discount 5%',
            'type' => 'percentage',
            'value' => 5,
            'description' => 'Promotional discount',
            'is_active' => true,
        ]);

        DiscountType::create([
            'code' => 'FLASH20',
            'name' => 'Flash Sale 20%',
            'type' => 'percentage',
            'value' => 20,
            'description' => 'Limited time flash sale',
            'is_active' => true,
        ]);

        DiscountType::create([
            'code' => 'FIXED50K',
            'name' => 'Fixed Discount Rp 50.000',
            'type' => 'fixed',
            'value' => 50000,
            'description' => 'Fixed amount discount',
            'is_active' => true,
        ]);

        DiscountType::create([
            'code' => 'FIXED100K',
            'name' => 'Fixed Discount Rp 100.000',
            'type' => 'fixed',
            'value' => 100000,
            'description' => 'Large fixed amount discount',
            'is_active' => true,
        ]);

        DiscountType::create([
            'code' => 'BULK10',
            'name' => 'Bulk Purchase 10%',
            'type' => 'percentage',
            'value' => 10,
            'description' => 'Discount for bulk purchases',
            'is_active' => true,
        ]);

        DiscountType::create([
            'code' => 'CLEARANCE30',
            'name' => 'Clearance Sale 30%',
            'type' => 'percentage',
            'value' => 30,
            'description' => 'End of season clearance',
            'is_active' => true,
        ]);

        DiscountType::factory(12)->create();

        DiscountType::factory(3)->inactive()->create();

        $this->command->info('Discount types created successfully!');
    }
}
