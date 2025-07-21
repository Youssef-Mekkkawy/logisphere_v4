<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consignee_notifies', function (Blueprint $table) {
            $table->id();
            $table->string('party_code')->unique();
            $table->string('party_name');
            $table->string('party_type'); // 'Consignee', 'Notify Party', 'Both'
            $table->string('company_registration')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('contact_person');
            $table->string('email');
            $table->string('phone');
            $table->string('fax')->nullable();
            $table->text('address');
            $table->string('city');
            $table->string('state_province')->nullable();
            $table->string('country');
            $table->string('postal_code')->nullable();
            $table->foreignId('country_id')->nullable()->constrained('countries');
            $table->string('preferred_language', 5)->default('en');
            $table->json('notification_preferences')->nullable(); // Email, SMS, Phone preferences
            $table->text('delivery_instructions')->nullable();
            $table->text('special_requirements')->nullable();
            $table->boolean('requires_original_docs')->default(true);
            $table->boolean('is_freight_forwarder')->default(false);
            $table->string('credit_rating')->nullable(); // A, B, C, etc.
            $table->decimal('credit_limit', 12, 2)->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('status')->default('Active');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['party_type', 'status']);
            $table->index(['country', 'status']);
            $table->index('party_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consignee_notifies');
    }
};
