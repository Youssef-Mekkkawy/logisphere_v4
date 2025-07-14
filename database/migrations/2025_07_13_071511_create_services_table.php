<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('service_code')->unique();
            $table->string('service_name');
            $table->string('service_category'); // 'Customs Clearance', 'Transportation', 'Warehousing', 'Documentation', 'Insurance', 'Inspection', 'Other'
            $table->text('description')->nullable();
            $table->text('detailed_description')->nullable();
            $table->string('billing_type'); // 'Fixed', 'Variable', 'Percentage', 'Hourly'
            $table->decimal('base_rate', 10, 2);
            $table->string('rate_currency', 3)->default('USD');
            $table->string('rate_unit')->nullable(); // 'per shipment', 'per container', 'per hour', 'per kg'
            $table->decimal('minimum_charge', 10, 2)->nullable();
            $table->decimal('maximum_charge', 10, 2)->nullable();
            $table->json('rate_tiers')->nullable(); // Tiered pricing structure
            $table->foreignId('account_id')->nullable()->constrained(); // GL account for revenue
            $table->string('tax_type')->nullable(); // 'VAT', 'Service Tax', 'Exempt'
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->boolean('is_mandatory')->default(false); // Required service
            $table->boolean('is_billable')->default(true); // Can be billed to client
            $table->boolean('requires_approval')->default(false);
            $table->json('required_documents')->nullable(); // Documents needed for service
            $table->integer('estimated_duration_hours')->nullable();
            $table->text('service_conditions')->nullable();
            $table->json('applicable_cargo_types')->nullable(); // Which cargo types need this
            $table->string('service_provider')->nullable(); // Internal, External, Both
            $table->string('status')->default('Active');
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['service_category', 'status']);
            $table->index(['billing_type', 'status']);
            $table->index('service_code');
            $table->index('is_mandatory');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
