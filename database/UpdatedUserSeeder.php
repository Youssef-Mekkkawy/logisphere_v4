<?php

// File: database/seeders/UpdatedUserSeeder.php (Fixed)
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Auth\User;
use App\Models\Auth\Role;
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
                'role_slug' => 'admin'
            ],
            [
                'name' => 'Operations Manager',
                'username' => 'ops_manager',
                'email' => 'operations@logisphere.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role_slug' => 'manager'
            ],
            [
                'name' => 'Customer Service',
                'username' => 'customer_service',
                'email' => 'cs@logisphere.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role_slug' => 'customer-service'
            ],
            [
                'name' => 'Finance Officer',
                'username' => 'finance',
                'email' => 'finance@logisphere.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role_slug' => 'finance'
            ],
            [
                'name' => 'Regular User',
                'username' => 'user',
                'email' => 'user@logisphere.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role_slug' => 'user'
            ],
        ];

        foreach ($users as $userData) {
            $roleSlug = $userData['role_slug'];
            unset($userData['role_slug']);

            // Create or update user
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            // Find the role by slug
            $role = Role::where('slug', $roleSlug)->first();

            if ($role) {
                // Remove existing roles to avoid duplicates
                $user->roles()->detach();

                // Attach the new role
                $user->roles()->attach($role->id);

                $this->command->info("Role '{$role->name}' assigned to user '{$user->name}'");
            } else {
                $this->command->warn("Role '{$roleSlug}' not found for user '{$user->name}'");
            }
        }

        $this->command->info('Users with roles seeded successfully!');
        $this->command->info('Default login: admin@logisphere.com / password');
    }
}
