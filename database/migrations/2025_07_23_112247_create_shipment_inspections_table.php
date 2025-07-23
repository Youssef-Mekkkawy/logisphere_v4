<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipment_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained('shipments')->onDelete('cascade');
            $table->foreignId('inspection_type_id')->constrained('inspection_types')->onDelete('cascade');
            $table->datetime('inspection_date')->nullable(); // When inspection was scheduled/started
            $table->datetime('completion_date')->nullable(); // When inspection was completed
            $table->string('status')->default('Pending'); // 'Pending', 'In Progress', 'Completed', 'Failed', 'Cancelled'
            $table->string('certificate_number')->nullable(); // Certificate or reference number
            $table->decimal('actual_cost', 10, 2)->nullable(); // Actual cost charged
            $table->decimal('actual_duration', 8, 2)->nullable(); // Actual hours taken
            $table->string('inspector_name')->nullable(); // Who performed the inspection
            $table->string('inspector_authority')->nullable(); // Which authority/company
            $table->text('notes')->nullable(); // Additional notes or findings
            $table->text('findings')->nullable(); // Inspection findings/results
            $table->json('documents_submitted')->nullable(); // Documents that were submitted
            $table->datetime('expiry_date')->nullable(); // When this inspection expires
            $table->boolean('passed')->nullable(); // Did the inspection pass (true/false/null for pending)
            $table->text('failure_reason')->nullable(); // Reason for failure if applicable
            $table->text('recommendations')->nullable(); // Inspector recommendations
            $table->timestamps();

            // Indexes for performance
            $table->index(['shipment_id', 'status']);
            $table->index(['inspection_type_id', 'status']);
            $table->index('inspection_date');
            $table->index('completion_date');
            $table->index('expiry_date');
            $table->index('status');

            // Ensure unique combination of shipment and inspection type
            $table->unique(['shipment_id', 'inspection_type_id'], 'unique_shipment_inspection');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_inspections');
    }
};
