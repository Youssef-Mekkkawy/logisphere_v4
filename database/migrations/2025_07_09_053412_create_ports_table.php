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
        Schema::create('ports', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('port_code', 10)->unique();
            $table->string('port_name');
            $table->enum('port_type', [
                'Seaport',
                'River Port',
                'Lake Port',
                'Inland Port',
                'Container Terminal',
                'Bulk Terminal',
                'Multi-Purpose',
                'Fishing Port',
                'Naval Base',
                'Ferry Terminal'
            ]);

            // Location Information
            $table->string('country', 100);
            $table->string('city', 100);
            $table->string('state_province', 100)->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->string('time_zone', 50)->nullable();

            // Contact Information
            $table->string('port_authority')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone', 20)->nullable();
            $table->string('website')->nullable();

            // Facilities and Services
            $table->json('facilities')->nullable(); // Container yards, warehouses, etc.
            $table->json('services')->nullable(); // Cargo handling, customs, etc.
            $table->json('terminal_operators')->nullable(); // Operating companies

            // Technical Specifications
            $table->string('max_vessel_size', 50)->nullable(); // e.g., "400m LOA"
            $table->decimal('max_draft_meters', 5, 2)->nullable();
            $table->integer('berth_count')->nullable();
            $table->decimal('storage_capacity', 15, 2)->nullable(); // TEU or m³
            $table->decimal('crane_capacity', 10, 2)->nullable(); // tonnes
            $table->json('working_hours')->nullable(); // Operating hours

            // Operational Status
            $table->enum('operational_status', [
                'Fully Operational',
                'Limited Operations',
                'Maintenance',
                'Weather Restriction',
                'Strike',
                'Emergency Closure',
                'Seasonal Closure'
            ])->default('Fully Operational');

            // Available Services
            $table->boolean('customs_available')->default(true);
            $table->boolean('quarantine_available')->default(false);
            $table->boolean('bunker_available')->default(false);
            $table->boolean('fresh_water_available')->default(true);

            // Transportation Connections
            $table->boolean('rail_connection')->default(false);
            $table->boolean('road_connection')->default(true);
            $table->decimal('airport_distance_km', 8, 2)->nullable();

            // Port Charges and Requirements
            $table->json('port_charges')->nullable(); // Different charge types
            $table->boolean('pilot_required')->default(false);
            $table->boolean('tugs_available')->default(false);
            $table->boolean('anchorage_available')->default(true);

            // Security
            $table->enum('security_level', [
                'ISPS Level 1',
                'ISPS Level 2',
                'ISPS Level 3',
                'Custom Security'
            ])->default('ISPS Level 1');

            // Port Categories
            $table->boolean('is_major_port')->default(false);
            $table->boolean('is_container_port')->default(true);
            $table->boolean('is_bulk_port')->default(false);
            $table->boolean('is_cruise_port')->default(false);

            // Equipment and Capabilities
            $table->json('handling_equipment')->nullable(); // Cranes, forklifts, etc.
            $table->json('cargo_types_handled')->nullable(); // Container, bulk, break bulk
            $table->json('restrictions')->nullable(); // Dangerous goods, size limits
            $table->json('weather_conditions')->nullable(); // Seasonal restrictions

            // Statistics and History
            $table->decimal('annual_throughput', 15, 2)->nullable(); // TEU/tonnes per year
            $table->year('established_year')->nullable();

            // System Fields
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->text('notes')->nullable();

            // Tenant isolation
            // $table->foreignId('tenant_id')->constrained()->onDelete('cascade');

            $table->timestamps();

            // Indexes for performance
            $table->index(['country', 'is_active']);
            $table->index(['port_type', 'is_active']);
            $table->index(['operational_status']);
            $table->index(['is_major_port']);
            $table->index(['is_container_port']);
            $table->index(['latitude', 'longitude']); // For geospatial queries
            $table->index(['sort_order', 'port_name']);
            // $table->index(['tenant_id', 'is_active']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ports');
    }
};
