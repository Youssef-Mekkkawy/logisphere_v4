<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('port_operations', function (Blueprint $table) {
            $table->id();
            $table->string('operation_id')->unique();
            $table->foreignId('port_id')->constrained('ports')->onDelete('cascade');
            $table->foreignId('shipment_id')->nullable()->constrained('shipments')->onDelete('cascade');

            // Operation Details
            $table->string('operation_type'); // Loading, Unloading, Transit, etc.
            $table->string('status')->default('Scheduled'); // Scheduled, In Progress, Completed, etc.
            $table->string('priority')->default('Medium'); // High, Medium, Low

            // Vessel Information
            $table->string('vessel_name');
            $table->string('vessel_imo')->nullable();
            $table->string('vessel_type'); // Container Ship, Bulk Carrier, etc.
            $table->decimal('vessel_size', 10, 2)->nullable(); // DWT
            $table->string('vessel_flag')->nullable();

            // Berth & Timing
            $table->string('berth_number')->nullable();
            $table->datetime('berth_assignment_time')->nullable();
            $table->datetime('scheduled_arrival');
            $table->datetime('actual_arrival')->nullable();
            $table->datetime('scheduled_departure')->nullable();
            $table->datetime('actual_departure')->nullable();
            $table->datetime('operation_start_time')->nullable();
            $table->datetime('operation_end_time')->nullable();

            // Cargo Information
            $table->string('cargo_type')->nullable(); // Container, Bulk, Liquid, etc.
            $table->decimal('cargo_volume', 12, 2)->nullable(); // Tons or TEU
            $table->integer('containers_count')->nullable();
            $table->json('container_types')->nullable(); // 20ft, 40ft, 40ft HC, etc.
            $table->json('handling_equipment_used')->nullable(); // Cranes, forklifts used

            // Personnel & Services
            $table->string('operator_name')->nullable();
            $table->boolean('pilot_required')->default(false);
            $table->string('pilot_name')->nullable();
            $table->boolean('tugboat_required')->default(false);
            $table->integer('tugboat_count')->default(0);

            // Financial
            $table->decimal('handling_cost', 10, 2)->nullable();
            $table->decimal('port_fees', 10, 2)->nullable();
            $table->decimal('additional_charges', 10, 2)->nullable();
            $table->decimal('total_cost', 10, 2)->nullable();

            // Compliance & Documentation
            $table->boolean('customs_cleared')->default(false);
            $table->boolean('quarantine_cleared')->default(false);
            $table->boolean('documents_complete')->default(false);

            // Operational Details
            $table->string('weather_conditions')->nullable();
            $table->text('operation_notes')->nullable();
            $table->text('special_requirements')->nullable();
            $table->string('delays_reason')->nullable();
            $table->decimal('delay_duration', 8, 2)->nullable(); // Hours

            $table->timestamps();

            // Indexes for performance
            $table->index(['port_id', 'status']);
            $table->index(['operation_type', 'status']);
            $table->index('scheduled_arrival');
            $table->index('actual_arrival');
            $table->index('vessel_type');
            $table->index('berth_number');
            $table->index(['status', 'priority']);
            $table->index('operation_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('port_operations');
    }
};
