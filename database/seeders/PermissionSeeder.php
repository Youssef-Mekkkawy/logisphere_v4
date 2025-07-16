<?php

// File: database/seeders/PermissionSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // User Management
            ['name' => 'View Users', 'slug' => 'users.view', 'group' => 'Users'],
            ['name' => 'Create Users', 'slug' => 'users.create', 'group' => 'Users'],
            ['name' => 'Edit Users', 'slug' => 'users.edit', 'group' => 'Users'],
            ['name' => 'Delete Users', 'slug' => 'users.delete', 'group' => 'Users'],
            ['name' => 'Manage User Roles', 'slug' => 'users.manage-roles', 'group' => 'Users'],

            // Role Management
            ['name' => 'View Roles', 'slug' => 'roles.view', 'group' => 'Roles'],
            ['name' => 'Create Roles', 'slug' => 'roles.create', 'group' => 'Roles'],
            ['name' => 'Edit Roles', 'slug' => 'roles.edit', 'group' => 'Roles'],
            ['name' => 'Delete Roles', 'slug' => 'roles.delete', 'group' => 'Roles'],
            ['name' => 'Manage Role Permissions', 'slug' => 'roles.manage-permissions', 'group' => 'Roles'],

            // Employee Management
            ['name' => 'View Employees', 'slug' => 'employees.view', 'group' => 'Employees'],
            ['name' => 'Create Employees', 'slug' => 'employees.create', 'group' => 'Employees'],
            ['name' => 'Edit Employees', 'slug' => 'employees.edit', 'group' => 'Employees'],
            ['name' => 'Delete Employees', 'slug' => 'employees.delete', 'group' => 'Employees'],
            ['name' => 'Manage Employee Assignments', 'slug' => 'employees.manage-assignments', 'group' => 'Employees'],

            // Company Management
            ['name' => 'View Companies', 'slug' => 'companies.view', 'group' => 'Companies'],
            ['name' => 'Create Companies', 'slug' => 'companies.create', 'group' => 'Companies'],
            ['name' => 'Edit Companies', 'slug' => 'companies.edit', 'group' => 'Companies'],
            ['name' => 'Delete Companies', 'slug' => 'companies.delete', 'group' => 'Companies'],
            ['name' => 'Manage Company Contracts', 'slug' => 'companies.manage-contracts', 'group' => 'Companies'],

            // Shipment Management
            ['name' => 'View Shipments', 'slug' => 'shipments.view', 'group' => 'Shipments'],
            ['name' => 'Create Shipments', 'slug' => 'shipments.create', 'group' => 'Shipments'],
            ['name' => 'Edit Shipments', 'slug' => 'shipments.edit', 'group' => 'Shipments'],
            ['name' => 'Delete Shipments', 'slug' => 'shipments.delete', 'group' => 'Shipments'],
            ['name' => 'Track Shipments', 'slug' => 'shipments.track', 'group' => 'Shipments'],
            ['name' => 'Manage Shipment Status', 'slug' => 'shipments.manage-status', 'group' => 'Shipments'],

            // Accounting
            ['name' => 'View Accounting', 'slug' => 'accounting.view', 'group' => 'Accounting'],
            ['name' => 'Create Transactions', 'slug' => 'accounting.create-transactions', 'group' => 'Accounting'],
            ['name' => 'Edit Transactions', 'slug' => 'accounting.edit-transactions', 'group' => 'Accounting'],
            ['name' => 'Delete Transactions', 'slug' => 'accounting.delete-transactions', 'group' => 'Accounting'],
            ['name' => 'Generate Reports', 'slug' => 'accounting.generate-reports', 'group' => 'Accounting'],
            ['name' => 'Manage Employee Advances', 'slug' => 'accounting.manage-advances', 'group' => 'Accounting'],

            // Settings Management
            ['name' => 'View Settings', 'slug' => 'settings.view', 'group' => 'Settings'],
            ['name' => 'Edit System Settings', 'slug' => 'settings.edit-system', 'group' => 'Settings'],
            ['name' => 'Manage Ports', 'slug' => 'settings.manage-ports', 'group' => 'Settings'],
            ['name' => 'Manage Shipping Agencies', 'slug' => 'settings.manage-agencies', 'group' => 'Settings'],
            ['name' => 'Manage Shipment Types', 'slug' => 'settings.manage-types', 'group' => 'Settings'],

            // Dashboard & Reports
            ['name' => 'View Dashboard', 'slug' => 'dashboard.view', 'group' => 'Dashboard'],
            ['name' => 'View Reports', 'slug' => 'reports.view', 'group' => 'Reports'],
            ['name' => 'Export Data', 'slug' => 'reports.export', 'group' => 'Reports'],
            ['name' => 'View Analytics', 'slug' => 'analytics.view', 'group' => 'Analytics'],

            // File Management
            ['name' => 'View Files', 'slug' => 'files.view', 'group' => 'Files'],
            ['name' => 'Upload Files', 'slug' => 'files.upload', 'group' => 'Files'],
            ['name' => 'Delete Files', 'slug' => 'files.delete', 'group' => 'Files'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        $this->command->info('Permissions seeded successfully!');
    }
}