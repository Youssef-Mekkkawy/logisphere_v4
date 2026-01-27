<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add name column to ports if it doesn't exist
        if (!Schema::hasColumn('ports', 'name')) {
            Schema::table('ports', function (Blueprint $table) {
                $table->string('name')->after('id');
            });
        }

        // Add name column to shipment_types
        if (!Schema::hasColumn('shipment_types', 'name')) {
            Schema::table('shipment_types', function (Blueprint $table) {
                $table->string('name')->after('id');
            });
        }

        // Add name column to shipping_agencies
        if (!Schema::hasColumn('shipping_agencies', 'name')) {
            Schema::table('shipping_agencies', function (Blueprint $table) {
                $table->string('name')->after('id');
            });
        }

        // Add more tables as needed...
    }

    public function down(): void
    {
        Schema::table('ports', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        Schema::table('shipment_types', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        Schema::table('shipping_agencies', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};
