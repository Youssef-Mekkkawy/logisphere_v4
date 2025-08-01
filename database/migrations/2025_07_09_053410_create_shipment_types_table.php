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
        Schema::create('shipment_types', function (Blueprint $table) {
            $table->id();
            $table->string('type_code', 20)->unique()->comment('Unique shipment type identifier');
            $table->string('type_name')->comment('Shipment type name/title');

            // Basic Classification
            $table->enum('category', [
                'Ocean Freight',
                'Air Freight',
                'Land Transport',
                'Rail Transport',
                'Multimodal',
                'Express',
                'Economy',
                'Special Handling',
                'Project Cargo',
                'Bulk Cargo',
                'Container',
                'Break Bulk',
                'Other' // ✅ add this
            ])->comment('Main category classification');
            $table->string('subcategory', 100)->nullable()->comment('Sub-category if applicable');
            $table->text('description')->nullable()->comment('Brief description');
            $table->text('detailed_description')->nullable()->comment('Detailed description');

            // Cargo Information
            $table->enum('cargo_type', [
                'General Cargo',
                'Dangerous Goods',
                'Refrigerated',
                'Liquid Bulk',
                'Dry Bulk',
                'Vehicles',
                'Heavy Machinery',
                'Electronics',
                'Pharmaceuticals',
                'Food Products',
                'Textiles',
                'Chemicals',
                'Raw Materials',
                'Finished Goods',
                'Perishables',
                'Bulk Cargo' // ✅ Add this
            ])->comment('Type of cargo handled');
            $table->json('container_types')->nullable()->comment('Applicable container types');

            // Transit Information
            $table->enum('transit_mode', ['Sea', 'Air', 'Road', 'Rail', 'Barge', 'Pipeline', 'Multimodal'])
                ->comment('Primary mode of transportation');
            $table->json('handling_requirements')->nullable()->comment('Special handling requirements');
            $table->json('documentation_required')->nullable()->comment('Required documentation');
            $table->text('special_instructions')->nullable()->comment('Special handling instructions');

            // Time Estimates
            $table->integer('estimated_transit_days')->nullable()->comment('Estimated transit time in days');
            $table->integer('min_transit_days')->nullable()->comment('Minimum transit time');
            $table->integer('max_transit_days')->nullable()->comment('Maximum transit time');

            // Restrictions
            $table->json('weight_restrictions')->nullable()->comment('Weight limitations (JSON)');
            $table->json('volume_restrictions')->nullable()->comment('Volume limitations (JSON)');
            $table->json('dimension_restrictions')->nullable()->comment('Dimension limitations (JSON)');

            // Special Characteristics
            $table->boolean('temperature_controlled')->default(false)->comment('Requires temperature control');
            $table->boolean('hazardous_material')->default(false)->comment('Handles dangerous goods');
            $table->boolean('high_value_cargo')->default(false)->comment('For high-value items');
            $table->boolean('fragile_cargo')->default(false)->comment('For fragile items');
            $table->boolean('oversized_cargo')->default(false)->comment('For oversized cargo');
            $table->boolean('requires_escort')->default(false)->comment('Requires security escort');

            // Service Levels
            $table->enum('customs_complexity', ['Simple', 'Standard', 'Complex', 'Very Complex'])->nullable()
                ->comment('Customs clearance complexity');
            $table->boolean('insurance_required')->default(false)->comment('Insurance mandatory');
            $table->enum('tracking_level', ['Basic', 'Standard', 'Advanced', 'Real-time'])->nullable()
                ->comment('Level of tracking provided');

            // Cost Information
            $table->decimal('cost_factor', 5, 2)->nullable()->comment('Cost multiplication factor');
            $table->decimal('base_rate_multiplier', 6, 3)->default(1.000)->comment('Base rate multiplier');
            $table->enum('priority_level', ['Low', 'Standard', 'High', 'Urgent', 'Critical'])->nullable()
                ->comment('Priority level classification');
            $table->enum('service_level', ['Basic', 'Standard', 'Premium', 'Express', 'Economy'])->nullable()
                ->comment('Service level offered');

            // Route and Availability
            $table->json('applicable_routes')->nullable()->comment('Applicable routes (JSON)');
            $table->json('seasonal_restrictions')->nullable()->comment('Seasonal limitations');
            $table->json('equipment_needed')->nullable()->comment('Required equipment');

            // Operational Instructions
            $table->text('loading_instructions')->nullable()->comment('Loading procedures');
            $table->text('unloading_instructions')->nullable()->comment('Unloading procedures');
            $table->text('storage_requirements')->nullable()->comment('Storage requirements');
            $table->json('packaging_requirements')->nullable()->comment('Packaging specifications');
            $table->json('labeling_requirements')->nullable()->comment('Labeling requirements');
            $table->json('certification_needed')->nullable()->comment('Required certifications');

            // Compliance Requirements
            $table->boolean('inspection_required')->default(false)->comment('Requires inspection');
            $table->boolean('quarantine_required')->default(false)->comment('Requires quarantine');
            $table->boolean('permit_required')->default(false)->comment('Requires special permits');

            // Booking and Operations
            $table->integer('booking_lead_time')->nullable()->comment('Required booking lead time (days)');
            $table->text('cutoff_requirements')->nullable()->comment('Booking cutoff requirements');
            $table->boolean('consolidation_allowed')->default(true)->comment('Allows consolidation');
            $table->boolean('partial_loads_allowed')->default(true)->comment('Allows partial loads');
            $table->boolean('return_loads_allowed')->default(false)->comment('Allows return cargo');
            $table->boolean('transshipment_allowed')->default(true)->comment('Allows transshipment');

            // Service Options
            $table->boolean('door_to_door_available')->default(false)->comment('Door-to-door service available');
            $table->boolean('port_to_port_only')->default(false)->comment('Port-to-port service only');
            $table->boolean('express_service_available')->default(false)->comment('Express service available');
            $table->boolean('economy_service_available')->default(false)->comment('Economy service available');
            $table->boolean('standard_service_available')->default(true)->comment('Standard service available');

            // Status and Validity
            $table->enum('status', ['Active', 'Inactive', 'Suspended', 'Discontinued', 'Under Review'])
                ->default('Active');
            $table->date('effective_from')->nullable()->comment('Effective start date');
            $table->date('effective_to')->nullable()->comment('Effective end date');
            $table->text('notes')->nullable()->comment('Additional notes');
                
            $table->timestamps();

            // Indexes for performance
            $table->index('type_code');
            $table->index(['category', 'status']);
            $table->index(['cargo_type', 'status']);
            $table->index(['transit_mode', 'status']);
            $table->index(['status', 'temperature_controlled']);
            $table->index(['status', 'hazardous_material']);
            $table->index(['status', 'express_service_available']);
            $table->index(['effective_from', 'effective_to']);
            $table->index('priority_level');
            $table->index('service_level');
            $table->index('customs_complexity');
            $table->index('tracking_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_types');
    }
};
