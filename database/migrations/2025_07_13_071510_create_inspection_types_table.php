<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspection_types', function (Blueprint $table) {
            $table->id();
            $table->string('inspection_code')->unique();
            $table->string('inspection_name');
            $table->string('inspection_category'); // 'Customs', 'Quality', 'Safety', 'Environmental', etc.
            $table->text('description')->nullable();
            $table->json('required_documents')->nullable(); // Array of required documents
            $table->decimal('estimated_duration', 8, 2)->nullable(); // Hours
            $table->decimal('cost_estimate', 10, 2)->nullable(); // USD
            $table->string('regulatory_authority')->nullable(); // Who performs the inspection
            $table->boolean('mandatory')->default(false); // Is this inspection mandatory
            $table->json('applies_to')->nullable(); // Shipment types this applies to
            $table->text('prerequisites')->nullable(); // What's needed before this inspection
            $table->integer('validity_period')->nullable(); // Days the inspection is valid
            $table->boolean('renewal_required')->default(false); // Can it be renewed
            $table->text('compliance_standards')->nullable(); // Standards it complies with
            $table->string('status')->default('Active');
            $table->timestamps();

            $table->index(['inspection_category', 'status']);
            $table->index('inspection_code');
            $table->index(['mandatory', 'status']);
            $table->index('regulatory_authority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspection_types');
    }
};
