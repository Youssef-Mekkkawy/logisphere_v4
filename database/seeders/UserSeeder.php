<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'System Administrator',
                'username' => 'admin',
                'email' => 'admin@logisphere.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Operations Manager',
                'username' => 'ops_manager',
                'email' => 'operations@logisphere.com',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Customer Service',
                'username' => 'customer_service',
                'email' => 'cs@logisphere.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Shipping Coordinator',
                'username' => 'shipping_coord',
                'email' => 'shipping@logisphere.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Finance Officer',
                'username' => 'finance',
                'email' => 'finance@logisphere.com',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $this->command->info('Users seeded successfully!');
        $this->command->info('Default login: admin@logisphere.com / password');
    }
}