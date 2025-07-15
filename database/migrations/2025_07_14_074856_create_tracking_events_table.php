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
        Schema::create('tracking_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete();
            $table->string('status', 100);
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->date('event_date');
            $table->timestamp('event_time')->nullable();
            $table->boolean('is_milestone')->default(false);
            $table->boolean('is_public')->default(true);
            $table->string('vessel_name')->nullable();
            $table->string('container_number')->nullable();
            $table->foreignId('port_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['shipment_id', 'event_date']);
            $table->index(['status', 'event_date']);
            $table->index(['is_public', 'event_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracking_events');
    }
};
