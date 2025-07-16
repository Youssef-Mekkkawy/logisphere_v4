<?php

// File: database/seeders/RoleSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Create Admin Role
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Administrator',
                'description' => 'Full system access with all permissions',
                'color' => '#dc2626',
                'is_system' => true,
            ]
        );

        // Give admin all permissions
        $adminRole->permissions()->sync(Permission::all());

        // Create Manager Role
        $managerRole = Role::firstOrCreate(
            ['slug' => 'manager'],
            [
                'name' => 'Manager',
                'description' => 'Can manage operations, employees, and shipments',
                'color' => '#d97706',
                'is_system' => true,
            ]
        );

        // Manager permissions
        $managerPermissions = [
            'dashboard.view',
            'employees.view',
            'employees.create',
            'employees.edit',
            'companies.view',
            'companies.create',
            'companies.edit',
            'shipments.view',
            'shipments.create',
            'shipments.edit',
            'shipments.track',
            'shipments.manage-status',
            'accounting.view',
            'accounting.create-transactions',
            'accounting.edit-transactions',
            'accounting.manage-advances',
            'reports.view',
            'reports.export',
            'analytics.view',
            'files.view',
            'files.upload'
        ];

        $managerRole->permissions()->sync(
            Permission::whereIn('slug', $managerPermissions)->pluck('id')
        );

        // Create User Role
        $userRole = Role::firstOrCreate(
            ['slug' => 'user'],
            [
                'name' => 'User',
                'description' => 'Basic user with limited permissions',
                'color' => '#059669',
                'is_system' => true,
            ]
        );

        // User permissions
        $userPermissions = [
            'dashboard.view',
            'employees.view',
            'companies.view',
            'shipments.view',
            'shipments.track',
            'accounting.view',
            'reports.view',
            'files.view'
        ];

        $userRole->permissions()->sync(
            Permission::whereIn('slug', $userPermissions)->pluck('id')
        );

        // Create Customer Service Role
        $customerServiceRole = Role::firstOrCreate(
            ['slug' => 'customer-service'],
            [
                'name' => 'Customer Service',
                'description' => 'Handle customer inquiries and basic shipment management',
                'color' => '#3b82f6',
                'is_system' => false,
            ]
        );

        $customerServicePermissions = [
            'dashboard.view',
            'companies.view',
            'companies.edit',
            'shipments.view',
            'shipments.create',
            'shipments.edit',
            'shipments.track',
            'reports.view',
            'files.view',
            'files.upload'
        ];

        $customerServiceRole->permissions()->sync(
            Permission::whereIn('slug', $customerServicePermissions)->pluck('id')
        );

        // Create Finance Role
        $financeRole = Role::firstOrCreate(
            ['slug' => 'finance'],
            [
                'name' => 'Finance Officer',
                'description' => 'Handle accounting and financial operations',
                'color' => '#8b5cf6',
                'is_system' => false,
            ]
        );

        $financePermissions = [
            'dashboard.view',
            'companies.view',
            'employees.view',
            'shipments.view',
            'shipments.track',
            'accounting.view',
            'accounting.create-transactions',
            'accounting.edit-transactions',
            'accounting.delete-transactions',
            'accounting.generate-reports',
            'accounting.manage-advances',
            'reports.view',
            'reports.export',
            'analytics.view',
            'files.view',
            'files.upload'
        ];

        $financeRole->permissions()->sync(
            Permission::whereIn('slug', $financePermissions)->pluck('id')
        );

        $this->command->info('Roles seeded successfully!');
    }
}
