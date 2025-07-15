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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->string('booking_reference')->nullable(); // Client reference
            $table->foreignId('shipment_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained(); // Client company
            $table->foreignId('shipping_agency_id')->nullable()->constrained();
            $table->string('vessel_name')->nullable();
            $table->string('voyage_number')->nullable();
            $table->date('booking_date');
            $table->date('estimated_departure')->nullable();
            $table->date('estimated_arrival')->nullable();
            $table->date('cut_off_date')->nullable(); // Container cut-off
            $table->string('service_type'); // 'FCL', 'LCL', 'Break Bulk'
            $table->integer('container_count')->default(1);
            $table->string('container_type')->nullable(); // '20GP', '40GP', '40HC'
            $table->decimal('cargo_weight', 10, 2)->nullable();
            $table->decimal('cargo_volume', 10, 2)->nullable();
            $table->string('commodity_description')->nullable();
            $table->string('incoterms')->nullable(); // FOB, CIF, etc.
            $table->string('status')->default('Pending');
            $table->text('special_instructions')->nullable();
            $table->boolean('is_confirmed')->default(false);
            $table->timestamp('confirmed_at')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users'); // ✅ Allow NULL
            $table->foreignId('created_by')->nullable()->constrained('users');   // ✅ Allow NULL
            $table->timestamps();

            $table->index(['status', 'booking_date']);
            $table->index('vessel_name');
            $table->index('service_type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
