<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ports', function (Blueprint $table) {
            $table->id();
            $table->string('port_code')->unique();
            $table->string('port_name');
            $table->string('city');
            $table->string('state_province')->nullable();
            $table->string('country');
            $table->foreignId('country_id')->nullable()->constrained('countries');
            $table->string('postal_code')->nullable();
            $table->text('address')->nullable();
            $table->string('port_type'); // 'Seaport', 'Airport', 'Dry Port', etc.
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Contact Information
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('website')->nullable();

            // Operational Information
            $table->string('operating_hours')->nullable();
            $table->string('time_zone')->nullable();
            $table->json('facilities')->nullable(); // Available facilities
            $table->json('services')->nullable(); // Available services

            // Capacity & Infrastructure
            $table->integer('max_capacity')->nullable(); // TEU capacity
            $table->integer('total_berths')->nullable();
            $table->decimal('max_vessel_size', 10, 2)->nullable(); // DWT
            $table->decimal('draft_depth', 8, 2)->nullable(); // meters
            $table->boolean('major_port')->default(false);

            // Services Available
            $table->boolean('customs_available')->default(true);
            $table->boolean('quarantine_available')->default(false);
            $table->boolean('pilotage_compulsory')->default(false);

            // Authority & Equipment
            $table->string('port_authority')->nullable();
            $table->json('handling_equipment')->nullable();
            $table->integer('storage_capacity')->nullable(); // m² or TEU

            // Connectivity
            $table->boolean('rail_connection')->default(false);
            $table->boolean('road_connection')->default(true);

            $table->string('status')->default('Active');
            $table->timestamps();

            // Indexes for performance
            $table->index(['country_id', 'status']);
            $table->index('port_type');
            $table->index('port_code');
            $table->index(['major_port', 'status']);
            $table->index(['city', 'country']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ports');
    }
};
