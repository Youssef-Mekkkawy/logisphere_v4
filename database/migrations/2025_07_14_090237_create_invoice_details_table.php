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
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->integer('line_number'); // Sequence within invoice
            $table->string('item_type'); // 'service', 'charge', 'product', 'expense'
            $table->string('item_code')->nullable();
            $table->string('description');
            $table->text('detailed_description')->nullable();
            $table->foreignId('account_id')->nullable()->constrained(); // GL Account
            $table->decimal('quantity', 10, 2)->default(1);
            $table->string('unit_of_measure')->nullable(); // 'per shipment', 'per container', 'per ton'
            $table->decimal('unit_price', 12, 2);
            $table->decimal('line_total', 12, 2);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->string('tax_type')->nullable(); // 'VAT', 'Service Tax', 'Exempt'
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('final_amount', 12, 2); // After discount and tax
            $table->string('currency', 3)->default('USD');
            $table->decimal('exchange_rate', 10, 4)->default(1);
            $table->foreignId('shipment_id')->nullable()->constrained();
            $table->json('cost_breakdown')->nullable(); // Detailed cost analysis
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['invoice_id', 'line_number']);
            $table->index('item_type');
            $table->index('account_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_details');
    }
};
