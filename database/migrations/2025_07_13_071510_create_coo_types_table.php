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
        Schema::create('coo_types', function (Blueprint $table) {
            $table->id();
            $table->string('type_code')->unique();
            $table->string('type_name');
            $table->string('full_name'); // Certificate of Origin full name
            $table->string('issuing_authority')->nullable();
            $table->string('country_code', 2)->nullable(); // ISO country code
            $table->text('description')->nullable();
            $table->boolean('requires_embassy_legalization')->default(false);
            $table->boolean('requires_chamber_attestation')->default(false);
            $table->decimal('processing_fee', 8, 2)->nullable();
            $table->integer('processing_days')->default(1);
            $table->json('required_documents')->nullable(); // List of required docs
            $table->string('template_path')->nullable(); // COO template file
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['country_code', 'is_active']);
            $table->index('type_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coo_types');
    }
};
