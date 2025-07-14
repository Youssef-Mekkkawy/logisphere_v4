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
            $table->string('type_code')->unique();
            $table->string('type_name');
            $table->string('measurement_type'); // 'Weight', 'Volume', 'Count', 'Area', 'Length'
            $table->string('unit'); // 'kg', 'ton', 'm³', 'pieces', 'm²', 'm'
            $table->string('unit_symbol', 10); // 'kg', 't', 'm³', 'pcs', 'm²', 'm'
            $table->text('description')->nullable();
            $table->string('cargo_category'); // 'General', 'Bulk', 'Container', 'Liquid', 'Hazardous'
            $table->decimal('conversion_factor', 10, 6)->default(1); // Convert to base unit
            $table->string('base_unit')->nullable(); // Base unit for conversion
            $table->boolean('allows_decimals')->default(true);
            $table->decimal('min_value', 15, 6)->nullable();
            $table->decimal('max_value', 15, 6)->nullable();
            $table->integer('decimal_places')->default(2);
            $table->json('applicable_container_types')->nullable(); // Which containers use this
            $table->string('billing_unit')->nullable(); // How this is billed
            $table->text('calculation_notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['measurement_type', 'is_active']);
            $table->index(['cargo_category', 'is_active']);
            $table->index('type_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quantity_types');
    }
};
