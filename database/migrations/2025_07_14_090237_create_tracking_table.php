<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tracking', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->unique();
            $table->morphs('trackable'); // shipment_id, container_id, booking_id
            $table->string('event_type'); // 'status_change', 'location_update', 'milestone', 'alert'
            $table->string('event_code'); // Standardized event codes
            $table->string('event_description');
            $table->string('status')->nullable(); // Current status after this event
            $table->string('location')->nullable(); // Where event occurred
            $table->foreignId('port_id')->nullable()->constrained();
            $table->string('facility')->nullable(); // Terminal, warehouse, etc.
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamp('event_datetime');
            $table->timestamp('estimated_datetime')->nullable();
            $table->timestamp('actual_datetime')->nullable();
            $table->string('reported_by')->nullable(); // System, Agent, Customer
            $table->foreignId('employee_id')->nullable()->constrained();
            $table->string('vessel_name')->nullable();
            $table->string('voyage_number')->nullable();
            $table->string('container_number')->nullable();
            $table->text('remarks')->nullable();
            $table->json('event_data')->nullable(); // Additional structured data
            $table->boolean('is_milestone')->default(false);
            $table->boolean('is_public')->default(true); // Visible to customer
            $table->boolean('send_notification')->default(false);
            $table->timestamps();

            $table->index(['trackable_type', 'trackable_id']);
            $table->index(['event_datetime', 'event_type']);
            $table->index('tracking_number');
            $table->index(['status', 'is_public']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tracking');
    }
};
