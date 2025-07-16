<?php

// File: database/seeders/UpdatedUserSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UpdatedUserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'System Administrator',
                'username' => 'admin',
                'email' => 'admin@logisphere.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'admin'
            ],
            [
                'name' => 'Operations Manager',
                'username' => 'ops_manager',
                'email' => 'operations@logisphere.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'manager'
            ],
            [
                'name' => 'Customer Service',
                'username' => 'customer_service',
                'email' => 'cs@logisphere.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'customer-service'
            ],
            [
                'name' => 'Finance Officer',
                'username' => 'finance',
                'email' => 'finance@logisphere.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'finance'
            ],
            [
                'name' => 'Regular User',
                'username' => 'user',
                'email' => 'user@logisphere.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'user'
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            // Assign role
            $roleModel = Role::where('slug', $role)->first();
            if ($roleModel) {
                $user->assignRole($roleModel);
            }
        }

        $this->command->info('Users with roles seeded successfully!');
        $this->command->info('Default login: admin@logisphere.com / password');
    }
}
