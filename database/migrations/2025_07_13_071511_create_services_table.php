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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('service_code', 50)->unique()->comment('Unique service identifier');
            $table->string('service_name')->comment('Service name/title');
            $table->enum('service_category', [
                'Customs Clearance',
                'Transportation',
                'Warehousing',
                'Documentation',
                'Insurance',
                'Inspection',
                'Cargo Handling',
                'Port Services',
                'Freight Forwarding',
                'Consulting',
                'Other'
            ])->comment('Service category classification');
            $table->text('description')->nullable()->comment('Brief service description');
            $table->text('detailed_description')->nullable()->comment('Detailed service description');

            // Billing Information
            $table->enum('billing_type', ['Fixed', 'Variable', 'Percentage', 'Hourly', 'Per Unit', 'Tiered'])
                ->comment('How the service is billed');
            $table->decimal('base_rate', 10, 2)->comment('Base rate for the service');
            $table->char('rate_currency', 3)->default('USD')->comment('Currency for rates');
            $table->string('rate_unit', 100)->nullable()->comment('Unit for billing (per shipment, per container, etc.)');
            $table->decimal('minimum_charge', 10, 2)->nullable()->comment('Minimum charge for service');
            $table->decimal('maximum_charge', 10, 2)->nullable()->comment('Maximum charge for service');
            $table->json('rate_tiers')->nullable()->comment('Tiered pricing structure (JSON)');

            // Accounting Information
            // $table->foreignId('account_id')->nullable()->constrained()->onDelete('set null')
            //     ->comment('GL account for revenue booking');
            $table->unsignedBigInteger('account_id')->nullable()->comment('GL account for revenue booking (TODO: Add foreign key when Account model exists)');
            $table->enum('tax_type', ['VAT', 'Service Tax', 'Sales Tax', 'Exempt'])->nullable()
                ->comment('Type of tax applicable');
            $table->decimal('tax_percentage', 5, 2)->default(0)->comment('Tax percentage');

            // Service Configuration
            $table->boolean('is_mandatory')->default(false)->comment('Required service for certain shipments');
            $table->boolean('is_billable')->default(true)->comment('Can be billed to clients');
            $table->boolean('requires_approval')->default(false)->comment('Requires management approval');
            $table->json('required_documents')->nullable()->comment('Documents required for service');
            $table->integer('estimated_duration_hours')->nullable()->comment('Estimated completion time');
            $table->text('service_conditions')->nullable()->comment('Terms and conditions');
            $table->json('applicable_cargo_types')->nullable()->comment('Applicable cargo types');

            // Service Provider Information
            $table->enum('service_provider', ['Internal', 'External', 'Both'])->default('Internal')
                ->comment('Who provides the service');

            // Status and Validity
            $table->enum('status', ['Active', 'Inactive', 'Suspended', 'Discontinued'])->default('Active');
            $table->date('effective_from')->nullable()->comment('Service effective start date');
            $table->date('effective_to')->nullable()->comment('Service effective end date');
            $table->text('notes')->nullable()->comment('Additional notes');

            $table->timestamps();

            // Indexes for performance
            $table->index('service_code');
            $table->index(['service_category', 'status']);
            $table->index(['billing_type', 'status']);
            $table->index(['status', 'is_mandatory']);
            $table->index(['status', 'is_billable']);
            $table->index(['status', 'service_provider']);
            $table->index(['effective_from', 'effective_to']);
            $table->index('service_provider');
            $table->index('is_mandatory');
            $table->index('is_billable');
            $table->index('requires_approval');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
