<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::create([
            'code' => 'CUST001',
            'name' => 'PT Maju Jaya Abadi',
            'address' => 'Jl. Sudirman No. 123',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Pusat',
            'district' => 'Tanah Abang',
            'sub_district' => 'Petamburan',
            'postal_code' => '10260',
            'phone' => '021-12345678',
            'email' => 'info@majujaya.com',
            'is_active' => true,
        ]);

        Customer::create([
            'code' => 'CUST002',
            'name' => 'CV Sejahtera Teknologi',
            'address' => 'Jl. Gatot Subroto No. 45',
            'province' => 'Jawa Barat',
            'city' => 'Bandung',
            'district' => 'Bandung Wetan',
            'sub_district' => 'Citarum',
            'postal_code' => '40115',
            'phone' => '022-87654321',
            'email' => 'contact@sejahteratek.com',
            'is_active' => true,
        ]);

        Customer::create([
            'code' => 'CUST003',
            'name' => 'Toko Elektronik Prima',
            'address' => 'Jl. Malioboro No. 78',
            'province' => 'Jawa Tengah',
            'city' => 'Yogyakarta',
            'district' => 'Jetis',
            'sub_district' => 'Cokrodiningratan',
            'postal_code' => '55233',
            'phone' => '0274-123456',
            'email' => 'prima.elektronik@gmail.com',
            'is_active' => true,
        ]);

        Customer::factory(47)->create();

        Customer::factory(3)->inactive()->create();

        $this->command->info('Customers created successfully!');
    }
}
