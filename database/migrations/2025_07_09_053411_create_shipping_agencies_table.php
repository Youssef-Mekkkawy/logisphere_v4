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
        Schema::create('shipping_agencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('Unique agency code');
            $table->string('name')->comment('Agency name');
            $table->string('contact_person')->nullable()->comment('Primary contact person');
            $table->string('email')->nullable()->comment('Contact email');
            $table->string('phone', 50)->nullable()->comment('Contact phone number');
            $table->foreignId('country_id')->nullable()->constrained()->onDelete('set null')->comment('Country where agency is located');
            $table->text('address')->nullable()->comment('Agency address');
            $table->enum('service_type', ['Ocean Freight', 'Air Freight', 'Land Transport', 'Full Service'])
                ->default('Ocean Freight')->comment('Type of service provided');
            $table->text('services_offered')->nullable()->comment('Detailed services description');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();

            // Indexes
            $table->index('code');
            $table->index('status');
            $table->index('country_id');
            $table->index('service_type');
            $table->index(['status', 'service_type']);
            $table->index(['status', 'country_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_agencies');
    }
};
