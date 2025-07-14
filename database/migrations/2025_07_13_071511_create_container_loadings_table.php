<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up(): void
    {
        Schema::create('container_loadings', function (Blueprint $table) {
            $table->id();
            $table->string('loading_point_code')->unique();
            $table->string('loading_point_name');
            $table->string('facility_type'); // 'CFS', 'Warehouse', 'Factory', 'Port Terminal', 'Depot'
            $table->string('operator_name');
            $table->string('contact_person');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('address');
            $table->string('city');
            $table->string('country');
            $table->string('postal_code')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->json('operating_hours')->nullable(); // Daily operating schedule
            $table->json('container_types_handled')->nullable(); // 20GP, 40GP, 40HC, etc.
            $table->integer('max_containers_per_day')->nullable();
            $table->json('equipment_available')->nullable(); // Cranes, forklifts, etc.
            $table->json('services_offered')->nullable(); // Stuffing, destuffing, storage, etc.
            $table->decimal('storage_rate_per_day', 8, 2)->nullable();
            $table->decimal('stuffing_rate', 8, 2)->nullable(); // Per container
            $table->decimal('destuffing_rate', 8, 2)->nullable(); // Per container
            $table->boolean('has_security')->default(true);
            $table->boolean('has_cctv')->default(false);
            $table->boolean('requires_appointment')->default(true);
            $table->integer('advance_booking_hours')->default(24);
            $table->text('access_instructions')->nullable();
            $table->text('safety_requirements')->nullable();
            $table->string('status')->default('Active');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['facility_type', 'status']);
            $table->index(['country', 'city']);
            $table->index('loading_point_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('container_loadings');
    }
};
