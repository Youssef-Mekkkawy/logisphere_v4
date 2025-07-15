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
        Schema::table('shipments', function (Blueprint $table) {
            // Add employee_id column
            $table->foreignId('employee_id')->nullable()->after('company_id')->constrained('employees')->nullOnDelete();

            // Add shipping_agency_id and shipment_type_id if they don't exist
            if (!Schema::hasColumn('shipments', 'shipping_agency_id')) {
                $table->foreignId('shipping_agency_id')->nullable()->after('employee_id')->constrained('shipping_agencies')->nullOnDelete();
            }

            if (!Schema::hasColumn('shipments', 'shipment_type_id')) {
                $table->foreignId('shipment_type_id')->nullable()->after('shipping_agency_id')->constrained('shipment_types')->nullOnDelete();
            }

            // Add other missing fields from your controller
            if (!Schema::hasColumn('shipments', 'container_number')) {
                $table->string('container_number')->nullable()->after('container_type');
            }

            if (!Schema::hasColumn('shipments', 'reference_number')) {
                $table->string('reference_number')->nullable()->after('container_number');
            }

            if (!Schema::hasColumn('shipments', 'etd')) {
                $table->date('etd')->nullable()->after('shipping_date');
            }

            if (!Schema::hasColumn('shipments', 'value')) {
                $table->decimal('value', 15, 2)->nullable()->after('volume');
            }

            if (!Schema::hasColumn('shipments', 'currency')) {
                $table->string('currency', 3)->nullable()->after('value');
            }

            if (!Schema::hasColumn('shipments', 'consignee_name')) {
                $table->string('consignee_name')->nullable()->after('cargo_description');
            }

            if (!Schema::hasColumn('shipments', 'consignee_address')) {
                $table->text('consignee_address')->nullable()->after('consignee_name');
            }

            if (!Schema::hasColumn('shipments', 'notify_party')) {
                $table->string('notify_party')->nullable()->after('consignee_address');
            }

            // Add indexes for better performance
            $table->index(['employee_id', 'status']);
            $table->index(['shipping_agency_id', 'status']);
            $table->index(['shipment_type_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['shipping_agency_id']);
            $table->dropForeign(['shipment_type_id']);

            // Drop indexes
            $table->dropIndex(['employee_id', 'status']);
            $table->dropIndex(['shipping_agency_id', 'status']);
            $table->dropIndex(['shipment_type_id', 'status']);

            // Drop columns
            $table->dropColumn([
                'employee_id',
                'shipping_agency_id',
                'shipment_type_id',
                'container_number',
                'reference_number',
                'etd',
                'value',
                'currency',
                'consignee_name',
                'consignee_address',
                'notify_party'
            ]);
        });
    }
};
