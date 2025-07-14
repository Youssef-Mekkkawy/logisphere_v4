# Laravel Users Migration Fix

## Issue: Laravel already creates a default users migration

Laravel automatically creates a users migration when you install it. We need to either:
1. Modify the existing migration, or
2. Create a new migration to add our custom fields

## Solution 1: Modify Existing Users Migration

**File:** `database/migrations/2014_10_12_000000_create_users_table.php`

```php
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique(); // Add this line
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'manager', 'user'])->default('user'); // Add this line
            $table->timestamp('last_login')->nullable(); // Add this line
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
