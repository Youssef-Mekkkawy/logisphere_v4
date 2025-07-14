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
        Schema::create('containers', function (Blueprint $table) {
            $table->id();
            $table->string('container_number')->unique();
            $table->string('container_type'); // '20GP', '40GP', '40HC', '40OT', '20RF'
            $table->foreignId('booking_id')->nullable()->constrained();
            $table->foreignId('shipment_id')->nullable()->constrained();
            $table->string('seal_number')->nullable();
            $table->string('tare_weight')->nullable(); // Empty container weight
            $table->decimal('gross_weight', 10, 2)->nullable(); // Loaded weight
            $table->decimal('net_weight', 10, 2)->nullable(); // Cargo weight
            $table->decimal('volume_used', 8, 2)->nullable(); // Cubic meters used
            $table->string('loading_status')->default('Empty'); // Empty, Loading, Loaded, Sealed
            $table->string('container_condition')->default('Good'); // Good, Damaged, Repair Needed
            $table->string('current_location')->nullable();
            $table->foreignId('current_port_id')->nullable()->constrained('ports');
            $table->date('stuffing_date')->nullable(); // Loading date
            $table->date('destuffing_date')->nullable(); // Unloading date
            $table->string('temperature_setting')->nullable(); // For reefer containers
            $table->text('damage_description')->nullable();
            $table->json('cargo_manifest')->nullable(); // List of cargo items
            $table->string('status')->default('Available');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['status', 'container_type']);
            $table->index('current_location');
            $table->index('loading_status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('containers');
    }
};
