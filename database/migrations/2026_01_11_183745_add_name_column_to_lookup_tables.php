<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // List of all tables that need 'name' column
        $tables = [
            'ports',
            'shipment_types',
            'shippers',
            'shipping_agencies',
            'coo_types',
            'inspection_types',
            'destinations',
            'consignees',
            'load_containers',
            'quantity_types',
            'jobs',
            'advance_types',
            'services'
        ];

        foreach ($tables as $table) {
            // Only add if table exists and doesn't have 'name' column
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'name')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->string('name')->nullable()->after('id');
                    $table->index('name'); // Add index for better performance
                });
            }
        }
    }

    public function down(): void
    {
        $tables = [
            'ports',
            'shipment_types',
            'shippers',
            'shipping_agencies',
            'coo_types',
            'inspection_types',
            'destinations',
            'consignees',
            'load_containers',
            'quantity_types',
            'jobs',
            'advance_types',
            'services'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'name')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropIndex(['name']);
                    $table->dropColumn('name');
                });
            }
        }
    }
};
