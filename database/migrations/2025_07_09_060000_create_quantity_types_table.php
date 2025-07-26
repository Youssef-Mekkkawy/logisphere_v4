<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quantity_types', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('quantity_code', 10)->unique();
            $table->string('quantity_name');
            $table->string('quantity_category'); // Container, Weight, Volume, Count, Area, etc.
            $table->string('unit_of_measure');
            $table->string('unit_symbol', 10);
            $table->string('base_unit')->nullable();

            // Conversion & Calculation
            $table->decimal('conversion_factor', 12, 6)->default(1.0);
            $table->tinyInteger('decimal_places')->default(2);
            $table->text('description')->nullable();
            $table->text('calculation_method')->nullable();

            // JSON Fields for Complex Data
            $table->json('applicable_cargo_types')->nullable(); // ['Containers', 'General Cargo']
            $table->json('industry_standards')->nullable(); // ['ISO 668', 'CSC Convention']
            $table->json('common_ranges')->nullable(); // ['min' => 1, 'max' => 50000]
            $table->json('validation_rules')->nullable(); // ['positive_integer']

            // Display & Formatting
            $table->string('display_format', 50)->nullable(); // '%.0f TEU'
            $table->string('reporting_category')->nullable();

            // Type Classifications
            $table->boolean('is_weight_based')->default(false);
            $table->boolean('is_volume_based')->default(false);
            $table->boolean('is_count_based')->default(false);
            $table->boolean('is_dimension_based')->default(false);

            // Calculation Properties
            $table->boolean('allows_fractions')->default(true);
            $table->boolean('requires_dimensions')->default(false);
            $table->boolean('auto_calculate')->default(false);

            // Billing & Commercial
            $table->boolean('is_billable')->default(true);
            $table->decimal('billing_multiplier', 8, 4)->default(1.0);
            $table->decimal('minimum_chargeable', 12, 6)->default(0.001);
            $table->string('rounding_method')->default('nearest'); // up, down, nearest

            // System Properties
            $table->boolean('is_standard')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(999);
            $table->text('notes')->nullable();

            $table->timestamps();

            // Indexes for performance
            $table->index(['quantity_category', 'is_active'], 'qty_types_category_active_idx');
            $table->index('quantity_code', 'qty_types_code_idx');
            $table->index(['is_standard', 'is_active'], 'qty_types_standard_active_idx');
            $table->index('sort_order', 'qty_types_sort_idx');
            $table->index('is_billable', 'qty_types_billable_idx');
            $table->index(['is_weight_based', 'is_volume_based', 'is_count_based'], 'qty_types_measurement_types_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quantity_types');
    }
};
