<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, let's check if the role column exists and remove it
        if (Schema::hasColumn('users', 'role')) {
            // Before dropping the column, let's migrate any existing role data to RBAC
            $this->migrateRoleDataToRBAC();

            // Now drop the role column
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }

        // Ensure the users table has all required columns for RBAC
        Schema::table('users', function (Blueprint $table) {
            // Make sure we have all the columns we need
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->after('name');
            }

            if (!Schema::hasColumn('users', 'last_login')) {
                $table->timestamp('last_login')->nullable()->after('password');
            }
        });

        // Ensure RBAC tables exist
        $this->ensureRBACTablesExist();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the role column if needed (for rollback)
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['admin', 'manager', 'user'])->default('user')->after('password');
            });
        }

        // Migrate RBAC data back to simple role column
        $this->migrateRBACDataToRole();
    }

    /**
     * Migrate existing role data to RBAC system
     */
    private function migrateRoleDataToRBAC(): void
    {
        // Get all users with roles
        $users = DB::table('users')->whereNotNull('role')->get();

        foreach ($users as $user) {
            // Find the corresponding role in RBAC system
            $roleId = DB::table('roles')->where('slug', $user->role)->value('id');

            if ($roleId) {
                // Check if user_role relationship already exists
                $existingUserRole = DB::table('user_roles')
                    ->where('user_id', $user->id)
                    ->where('role_id', $roleId)
                    ->first();

                if (!$existingUserRole) {
                    // Create user_role relationship
                    DB::table('user_roles')->insert([
                        'user_id' => $user->id,
                        'role_id' => $roleId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }
    }

    /**
     * Migrate RBAC data back to simple role column (for rollback)
     */
    private function migrateRBACDataToRole(): void
    {
        // Get all user-role relationships
        $userRoles = DB::table('user_roles')
            ->join('roles', 'user_roles.role_id', '=', 'roles.id')
            ->select('user_roles.user_id', 'roles.slug as role_slug')
            ->get();

        foreach ($userRoles as $userRole) {
            // Update user with their primary role
            DB::table('users')
                ->where('id', $userRole->user_id)
                ->update(['role' => $userRole->role_slug]);
        }
    }

    /**
     * Ensure RBAC tables exist
     */
    private function ensureRBACTablesExist(): void
    {
        // Create roles table if it doesn't exist
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('color', 7)->default('#3b82f6');
                $table->boolean('is_active')->default(true);
                $table->boolean('is_system')->default(false);
                $table->timestamps();
            });
        }

        // Create permissions table if it doesn't exist
        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('group');
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['group', 'is_active']);
            });
        }

        // Create role_permissions table if it doesn't exist
        if (!Schema::hasTable('role_permissions')) {
            Schema::create('role_permissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('role_id')->constrained()->onDelete('cascade');
                $table->foreignId('permission_id')->constrained()->onDelete('cascade');
                $table->timestamps();

                $table->unique(['role_id', 'permission_id']);
            });
        }

        // Create user_roles table if it doesn't exist
        if (!Schema::hasTable('user_roles')) {
            Schema::create('user_roles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('role_id')->constrained()->onDelete('cascade');
                $table->timestamps();

                $table->unique(['user_id', 'role_id']);
            });
        }
    }
};
