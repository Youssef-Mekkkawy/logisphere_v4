<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add user account management fields
            $table->boolean('is_active')->default(true)->after('email_verified_at');
            $table->boolean('force_password_change')->default(false)->after('is_active');
            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('set null')->after('force_password_change');

            // Add indexes for better performance
            $table->index('is_active');
            $table->index('force_password_change');
        });

        // Update employees table to have user_id reference
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->after('employee_id');
                $table->index('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['force_password_change']);
            $table->dropColumn(['is_active', 'force_password_change', 'employee_id']);
        });

        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropIndex(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};
