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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->string('invoice_type'); // 'sales', 'purchase', 'credit_note', 'debit_note'
            $table->foreignId('company_id')->constrained(); // Bill to company
            $table->foreignId('shipment_id')->nullable()->constrained();
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->string('payment_terms')->nullable(); // '30 days', 'COD', etc.
            $table->string('currency', 3)->default('USD');
            $table->decimal('exchange_rate', 10, 4)->default(1);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('balance_amount', 12, 2)->default(0);
            $table->string('status')->default('Draft'); // Draft, Sent, Paid, Overdue, Cancelled
            $table->text('notes')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['invoice_type', 'status']);
            $table->index(['company_id', 'invoice_date']);
            $table->index('due_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};
