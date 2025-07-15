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
        Schema::table('employees', function (Blueprint $table) {
            // Add missing personal information columns
            $table->string('emergency_contact')->nullable()->after('phone');
            $table->text('address')->nullable()->after('emergency_contact');
            $table->date('date_of_birth')->nullable()->after('address');
            $table->string('bank_account')->nullable()->after('date_of_birth');
            $table->string('nationality')->nullable()->after('bank_account'); // As string field

            // Add indexes for better performance
            $table->index(['department', 'status']);
            $table->index('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Drop the indexes first
            $table->dropIndex(['department', 'status']);
            $table->dropIndex(['employee_id']);

            // Drop the columns
            $table->dropColumn([
                'emergency_contact',
                'address',
                'date_of_birth',
                'bank_account',
                'nationality'
            ]);
        });
    }
};
