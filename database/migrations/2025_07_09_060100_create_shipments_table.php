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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();

            // Shipper & Consignee Information (placed early in table structure)
            $table->foreignId('shipper_id')->nullable()->constrained('shippers')->onDelete('set null');
            $table->foreignId('consignee_id')->nullable()->constrained('shippers')->onDelete('set null');
            // Common missing foreign keys in shipments
            $table->foreignId('destination_id')->nullable()->constrained('destinations')->nullOnDelete();
            $table->foreignId('consignee_notify_id')->nullable()->constrained('consignee_notifies')->nullOnDelete();
            $table->foreignId('coo_type_id')->nullable()->constrained('coo_types')->nullOnDelete();
            $table->foreignId('inspection_type_id')->nullable()->constrained('inspection_types')->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->foreignId('container_load_id')->nullable()->constrained('container_loadings')->nullOnDelete();
            $table->foreignId('loading_point_id')->nullable()->constrained('container_loadings')->nullOnDelete();

            // Basic Shipment Information
            $table->string('shipment_id')->unique();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('origin_port_id')->constrained('ports')->onDelete('cascade');
            $table->foreignId('destination_port_id')->constrained('ports')->onDelete('cascade');

            // Container & Type Information
            $table->string('container_type')->nullable();

            // Dates
            $table->date('shipping_date')->nullable();
            $table->date('eta')->nullable();

            // Financial
            $table->decimal('freight_cost', 10, 2)->nullable();

            // Cargo Information
            $table->text('cargo_description')->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->decimal('volume', 10, 2)->nullable();

            // Quantity Information
            $table->foreignId('quantity_type_id')->nullable()->constrained('quantity_types')->nullOnDelete();
            $table->decimal('total_quantity', 15, 6)->nullable();

            // Additional Information
            $table->text('special_instructions')->nullable();
            $table->enum('status', ['Pending', 'In Transit', 'At Port', 'Delivered', 'Cancelled'])->default('Pending');
            $table->date('departure_date')->nullable();
            $table->date('arrival_date')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['quantity_type_id', 'status']);
            $table->index('total_quantity');
            $table->index(['company_id', 'status']);
            $table->index(['origin_port_id', 'destination_port_id']);
            $table->index('shipment_id');
            $table->index('shipper_id');
            $table->index('consignee_id');
            $table->index('destination_id');
            $table->index('coo_type_id');
            $table->index('inspection_type_id');
            $table->index('service_id');
            $table->index('container_load_id');
            $table->index(['loading_point_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
