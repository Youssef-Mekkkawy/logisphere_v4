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
        Schema::create('quantity_types', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('quantity_code', 20)->unique();
            $table->string('quantity_name');
            $table->enum('quantity_category', [
                'Weight',
                'Volume',
                'Count',
                'Dimension',
                'Container',
                'Liquid',
                'Area',
                'Time',
                'Custom'
            ]);
            $table->string('unit_of_measure', 100);
            $table->string('unit_symbol', 20)->nullable();

            // Conversion & Calculation
            $table->string('base_unit', 50)->nullable(); // For unit conversion
            $table->decimal('conversion_factor', 12, 6)->nullable(); // Multiplier to base unit
            $table->integer('decimal_places')->default(2); // Display precision
            $table->text('description')->nullable();
            $table->text('calculation_method')->nullable(); // How to calculate this quantity

            // Applicability & Standards
            $table->json('applicable_cargo_types')->nullable(); // Which cargo types use this
            $table->json('industry_standards')->nullable(); // ISO, IMDG, etc.
            $table->json('common_ranges')->nullable(); // Min/max typical values
            $table->json('validation_rules')->nullable(); // Custom validation rules

            // Display & Reporting
            $table->string('display_format', 100)->nullable(); // Custom format string
            $table->string('reporting_category', 100)->nullable(); // For grouping in reports
            $table->string('customs_code', 50)->nullable(); // Customs/regulatory code

            // Type Indicators
            $table->boolean('is_weight_based')->default(false);
            $table->boolean('is_volume_based')->default(false);
            $table->boolean('is_count_based')->default(false);
            $table->boolean('is_dimension_based')->default(false);

            // Behavior Flags
            $table->boolean('allows_fractions')->default(true);
            $table->boolean('requires_dimensions')->default(false); // Needs L×W×H
            $table->boolean('auto_calculate')->default(false); // Auto-calc from other fields

            // Billing Configuration
            $table->boolean('is_billable')->default(true);
            $table->decimal('billing_multiplier', 8, 4)->nullable(); // Billing rate multiplier
            $table->decimal('minimum_chargeable', 12, 2)->nullable(); // Minimum charge qty
            $table->enum('rounding_method', ['up', 'down', 'nearest'])->default('nearest');

            // System Fields
            $table->boolean('is_standard')->default(false); // Industry standard unit
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->text('notes')->nullable();

            // Tenant isolation
            // $table->foreignId('tenant_id')->constrained()->onDelete('cascade');

            $table->timestamps();

            // Indexes for performance
            $table->index(['quantity_category', 'is_active']);
            $table->index(['is_standard', 'is_active']);
            $table->index(['is_billable']);
            $table->index(['is_weight_based']);
            $table->index(['is_volume_based']);
            $table->index(['is_count_based']);
            $table->index(['base_unit']); // For conversion queries
            $table->index(['sort_order', 'quantity_name']);
            // $table->index(['tenant_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quantity_types');
    }
};
