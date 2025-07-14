<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('destination_code')->unique();
            $table->string('destination_name');
            $table->string('city');
            $table->string('state_province')->nullable();
            $table->string('country');
            $table->string('postal_code')->nullable();
            $table->text('address')->nullable();
            $table->string('destination_type'); // 'Port', 'Airport', 'Warehouse', 'Factory', 'City'
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->text('delivery_instructions')->nullable();
            $table->text('access_restrictions')->nullable(); // Delivery time restrictions
            $table->json('facilities')->nullable(); // Available facilities
            $table->boolean('requires_appointment')->default(false);
            $table->string('timezone')->nullable();
            $table->string('status')->default('Active');
            $table->timestamps();

            $table->index(['country', 'status']);
            $table->index('destination_type');
            $table->index('destination_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
