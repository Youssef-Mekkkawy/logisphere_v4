<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // First, ensure roles exist (should be created by RoleSeeder)
        $this->createRolesIfNotExist();

        $users = [
            [
                'name' => 'System Administrator',
                'username' => 'admin',
                'email' => 'admin@logisphere.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'gender' => 'male',
                'role_slug' => 'admin'  // Will be used to assign role
            ],
            [
                'name' => 'Operations Manager',
                'username' => 'ops_manager',
                'email' => 'operations@logisphere.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'gender' => 'female',
                'role_slug' => 'manager'
            ],
            [
                'name' => 'Customer Service',
                'username' => 'customer_service',
                'email' => 'cs@logisphere.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'gender' => 'male',
                'role_slug' => 'user'
            ],
            [
                'name' => 'Shipping Coordinator',
                'username' => 'shipping_coord',
                'email' => 'shipping@logisphere.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'gender' => 'female',
                'role_slug' => 'user'  // Changed from 'coordinator' to 'user'
            ],
            [
                'name' => 'Finance Officer',
                'username' => 'finance',
                'email' => 'finance@logisphere.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'gender' => 'female',
                'role_slug' => 'finance'
            ],
        ];

        foreach ($users as $userData) {
            $roleSlug = $userData['role_slug'];
            unset($userData['role_slug']); // Remove from user data

            // Create or update user (but only create, don't update to avoid role column issue)
            $user = User::where('email', $userData['email'])->first();

            if (!$user) {
                // Create new user
                $user = User::create($userData);
            } else {
                // Update existing user but only the safe fields
                $user->update([
                    'name' => $userData['name'],
                    'username' => $userData['username'],
                    'email_verified_at' => $userData['email_verified_at']
                ]);
            }

            // Assign role using the RBAC system
            $role = Role::where('slug', $roleSlug)->first();
            if ($role && !$user->roles()->where('role_id', $role->id)->exists()) {
                $user->roles()->attach($role->id);
            }
        }

        $this->command->info('Users seeded successfully!');
        $this->command->info('Default login: admin@logisphere.com / password');
    }

    /**
     * Create basic roles if they don't exist
     */
    private function createRolesIfNotExist()
    {
        $roles = [
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'Full system access',
                'color' => '#dc2626',


                'is_system' => true
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Department management access',
                'color' => '#ea580c',

                'is_system' => true
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'description' => 'Basic user access',
                'color' => '#6b7280',


                'is_system' => true
            ],
            [
                'name' => 'Finance',
                'slug' => 'finance',
                'description' => 'Financial operations access',
                'color' => '#16a34a',


                'is_system' => false
            ],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );
        }
    }
}
