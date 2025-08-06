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
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            // Common missing foreign keys in shipments
            $table->foreignId('destination_id')->nullable()->constrained('destinations')->nullOnDelete();
            $table->foreignId('consignee_notify_id')->nullable()->constrained('consignee_notifies')->nullOnDelete();
            $table->foreignId('coo_type_id')->nullable()->constrained('coo_types')->nullOnDelete();
            $table->foreignId('inspection_type_id')->nullable()->constrained('inspection_types')->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->foreignId('container_load_id')->nullable()->constrained('container_loadings')->nullOnDelete();
            $table->string('route_code')->unique();
            $table->string('route_name');
            $table->foreignId('origin_port_id')->constrained('ports');
            $table->foreignId('destination_port_id')->constrained('ports');
            $table->string('service_type'); // 'Ocean', 'Air', 'Land', 'Multimodal'
            $table->string('transport_mode'); // 'Container', 'Bulk', 'Break Bulk'
            $table->integer('transit_days'); // Estimated transit time
            $table->integer('frequency_days')->nullable(); // Service frequency
            $table->decimal('distance_km', 10, 2)->nullable();
            $table->decimal('distance_nm', 10, 2)->nullable(); // Nautical miles
            $table->boolean('is_direct')->default(true);
            $table->json('intermediate_ports')->nullable(); // Port IDs for stops
            $table->json('route_schedule')->nullable(); // Departure days, times
            $table->string('carrier_preference')->nullable();
            $table->decimal('base_rate', 12, 2)->nullable();
            $table->string('rate_currency', 3)->default('USD');
            $table->string('rate_unit')->nullable(); // 'per container', 'per ton'
            $table->text('route_notes')->nullable();
            $table->string('status')->default('Active');
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
            $table->timestamps();

            $table->index(['origin_port_id', 'destination_port_id']);
            $table->index(['service_type', 'status']);
            $table->index('destination_id');
            $table->index('coo_type_id');
            $table->index('inspection_type_id');
            $table->index('service_id');
            $table->index('container_load_id');
            $table->index('route_code');
        });
    }

    public function down()
    {
        Schema::dropIfExists('routes');
    }
};
