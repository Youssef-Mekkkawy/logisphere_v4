<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shippers', function (Blueprint $table) {
            $table->id();
            $table->string('shipper_code')->unique();
            $table->string('shipper_name');
            $table->string('company_name');
            $table->string('shipper_type'); // 'Manufacturer', 'Exporter', 'Trading Company', 'Freight Forwarder', etc.
            $table->string('business_license')->nullable();
            $table->string('tax_id')->nullable();

            // Location Information
            $table->string('address');
            $table->string('city');
            $table->string('state_province')->nullable();
            $table->string('country');
            $table->foreignId('country_id')->nullable()->constrained('countries');
            $table->string('postal_code')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Contact Information
            $table->string('contact_person');
            $table->string('contact_phone');
            $table->string('contact_email');
            $table->string('website')->nullable();
            $table->string('alternative_phone')->nullable();
            $table->string('emergency_contact')->nullable();

            // Business Information
            $table->string('industry_type')->nullable();
            $table->text('specialization')->nullable(); // What they specialize in shipping
            $table->json('cargo_types')->nullable(); // Types of cargo they handle
            $table->json('trade_routes')->nullable(); // Primary trade routes
            $table->json('certifications')->nullable(); // ISO, quality certifications
            $table->year('established_year')->nullable();
            $table->integer('annual_volume')->nullable(); // TEU or tons per year

            // Operational Information
            $table->json('services_offered')->nullable(); // Services they provide
            $table->json('equipment_owned')->nullable(); // Equipment they own
            $table->json('preferred_ports')->nullable(); // Port IDs they frequently use
            $table->json('operating_regions')->nullable(); // Regions they operate in
            $table->string('operating_hours')->nullable();
            $table->string('time_zone')->nullable();

            // Financial Information
            $table->string('credit_rating')->nullable(); // A+, A, B+, etc.
            $table->string('payment_terms')->default('Net 30'); // Payment terms
            $table->decimal('credit_limit', 15, 2)->nullable();
            $table->string('currency_preference')->default('USD');
            $table->boolean('insurance_coverage')->default(false);
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();

            // Compliance & Documentation
            $table->json('required_documents')->nullable(); // Documents they typically need
            $table->boolean('customs_broker')->default(false);
            $table->boolean('freight_forwarder')->default(false);
            $table->boolean('dangerous_goods_certified')->default(false);
            $table->string('customs_code')->nullable();
            $table->json('regulatory_compliance')->nullable(); // Various compliance standards

            // Performance Metrics
            $table->decimal('on_time_delivery_rate', 5, 2)->nullable(); // Percentage
            $table->decimal('damage_rate', 5, 4)->nullable(); // Percentage
            $table->integer('total_shipments')->default(0);
            $table->decimal('customer_satisfaction', 3, 2)->nullable(); // Rating out of 5
            $table->date('last_audit_date')->nullable();
            $table->string('audit_result')->nullable();

            // Preferences & Settings
            $table->json('preferred_carriers')->nullable(); // Preferred shipping lines
            $table->json('preferred_incoterms')->nullable(); // FOB, CIF, etc.
            $table->boolean('track_and_trace_required')->default(true);
            $table->boolean('email_notifications')->default(true);
            $table->boolean('sms_notifications')->default(false);
            $table->string('notification_language')->default('en');

            // System Information
            $table->string('status')->default('Active'); // Active, Inactive, Suspended, Pending
            $table->text('notes')->nullable();
            $table->text('internal_notes')->nullable(); // Internal notes not visible to shipper
            $table->string('sales_representative')->nullable();
            $table->string('account_manager')->nullable();
            $table->date('contract_start_date')->nullable();
            $table->date('contract_end_date')->nullable();

            $table->timestamps();
            $table->softDeletes(); // Add soft deletes support

            // Indexes for performance
            $table->index(['country_id', 'status']);
            $table->index('shipper_type');
            $table->index('shipper_code');
            $table->index(['city', 'country']);
            $table->index('status');
            $table->index('credit_rating');
            $table->index(['established_year', 'annual_volume']);
            $table->index('sales_representative');
 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shippers');
    }
};
