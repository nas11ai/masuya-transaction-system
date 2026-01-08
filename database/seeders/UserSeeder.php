<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@masuya.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $superAdmin->assignRole('super-admin');

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@masuya.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        $cashier = User::create([
            'name' => 'Cashier User',
            'email' => 'cashier@masuya.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $cashier->assignRole('cashier');

        $stockManager = User::create([
            'name' => 'Stock Manager',
            'email' => 'stock@masuya.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $stockManager->assignRole('stock-manager');

        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@masuya.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $manager->assignRole('manager');

        User::factory(10)->create()->each(function ($user) {
            $user->assignRole('cashier');
        });

        $this->command->info('Users created successfully!');
    }
}
