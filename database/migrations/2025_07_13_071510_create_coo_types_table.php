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
            $table->string('code', 20)->unique()->comment('Unique COO type code');
            $table->string('name')->comment('COO type name');
            $table->string('issuing_authority')->comment('Authority that issues this COO');
            $table->boolean('is_mandatory')->default(false)->comment('Whether this COO is mandatory');
            $table->integer('processing_days')->comment('Processing time in days');
            $table->decimal('cost', 10, 2)->default(0)->comment('Cost in USD');
            $table->integer('validity_months')->default(12)->comment('Validity period in months, 0 = no expiry');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->text('description')->nullable()->comment('Description of the COO type');
            $table->text('required_documents')->nullable()->comment('Required documents for this COO');
            $table->timestamps();

            // Indexes
            $table->index('code');
            $table->index('status');
            $table->index('issuing_authority');
            $table->index(['status', 'is_mandatory']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coo_types');
    }
};
