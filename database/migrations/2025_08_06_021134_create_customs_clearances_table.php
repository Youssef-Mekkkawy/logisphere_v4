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
        Schema::create('customs_clearances', function (Blueprint $table) {
            $table->id();
            $table->string('clearance_number')->unique();
            $table->foreignId('shipment_id')->constrained('shipments')->onDelete('cascade');
            $table->foreignId('bosla_gomrok_id')->nullable()->constrained('bosla_gomrok')->nullOnDelete();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');

            // Clearance Information
            $table->enum('clearance_type', ['Import', 'Export', 'Transit', 'Temporary'])->default('Import');
            $table->string('customs_office');
            $table->string('declaration_number')->nullable();
            $table->date('declaration_date')->nullable();
            $table->date('clearance_date')->nullable();

            // Status and Processing
            $table->enum('status', ['Pending', 'In Process', 'Documents Submitted', 'Under Review', 'Cleared', 'On Hold', 'Rejected'])->default('Pending');
            $table->text('remarks')->nullable();
            $table->decimal('customs_value', 15, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->decimal('duties_paid', 10, 2)->default(0);
            $table->decimal('taxes_paid', 10, 2)->default(0);
            $table->decimal('fees_paid', 10, 2)->default(0);

            // Documents and References
            $table->json('required_documents')->nullable();
            $table->json('submitted_documents')->nullable();
            $table->text('special_instructions')->nullable();

            // Dates
            $table->datetime('submitted_at')->nullable();
            $table->datetime('processed_at')->nullable();
            $table->datetime('completed_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['status', 'clearance_type']);
            $table->index(['company_id', 'status']);
            $table->index(['shipment_id', 'status']);
            $table->index(['bosla_gomrok_id', 'status']);
            $table->index('clearance_number');
            $table->index('declaration_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customs_clearances');
    }
};
