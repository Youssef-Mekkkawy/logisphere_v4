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
        // First, clean up any salary values that exceed decimal(10, 2) limit
        DB::table('employees')
            ->where('salary', '>', 99999999.99)
            ->update(['salary' => 99999999.99]);

        // Also clean up any invalid salary values
        DB::table('employees')
            ->where('salary', '<', 0)
            ->update(['salary' => 0]);

        // Now the column change should work
        Schema::table('employees', function (Blueprint $table) {
            $table->decimal('salary', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->decimal('salary', 10, 2)->nullable()->change();
        });
    }
};
