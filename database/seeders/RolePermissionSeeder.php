<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            // Products
            'products.view',
            'products.create',
            'products.update',
            'products.delete',

            // Customers
            'customers.view',
            'customers.create',
            'customers.update',
            'customers.delete',

            // Transactions
            'transactions.view',
            'transactions.create',
            'transactions.update',
            'transactions.void',
            'transactions.delete',

            // Discounts
            'discounts.view',
            'discounts.manage',

            // Stock
            'stock.view',
            'stock.adjustment',

            // Users
            'users.view',
            'users.create',
            'users.update',
            'users.delete',

            // Roles
            'roles.view',
            'roles.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $superAdmin = Role::create(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'products.view',
            'products.create',
            'products.update',
            'products.delete',
            'customers.view',
            'customers.create',
            'customers.update',
            'customers.delete',
            'transactions.view',
            'transactions.create',
            'transactions.update',
            'transactions.void',
            'discounts.view',
            'discounts.manage',
            'stock.view',
            'stock.adjustment',
        ]);

        $cashier = Role::create(['name' => 'cashier']);
        $cashier->givePermissionTo([
            'products.view',
            'customers.view',
            'transactions.view',
            'transactions.create',
            'discounts.view',
            'stock.view',
        ]);

        $stockManager = Role::create(['name' => 'stock-manager']);
        $stockManager->givePermissionTo([
            'products.view',
            'products.create',
            'products.update',
            'stock.view',
            'stock.adjustment',
        ]);

        $manager = Role::create(['name' => 'manager']);
        $manager->givePermissionTo([
            'products.view',
            'customers.view',
            'transactions.view',
            'transactions.void',
            'discounts.view',
            'stock.view',
        ]);

        $this->command->info('Roles and Permissions created successfully!');
    }
}
