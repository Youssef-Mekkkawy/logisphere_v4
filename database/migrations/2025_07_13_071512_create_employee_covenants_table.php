<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_covenants', function (Blueprint $table) {
            $table->id();
            $table->string('covenant_number')->unique();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('item_type'); // 'Equipment', 'Vehicle', 'Card', 'Key', 'Document', 'Uniform'
            $table->string('item_name');
            $table->string('item_code')->nullable(); // Asset/equipment code
            $table->string('brand_model')->nullable();
            $table->string('serial_number')->nullable();
            $table->text('description')->nullable();
            $table->decimal('item_value', 10, 2)->nullable(); // Value of item
            $table->string('condition_received')->default('Good'); // Good, Fair, Poor, New
            $table->date('issued_date');
            $table->date('expected_return_date')->nullable();
            $table->date('actual_return_date')->nullable();
            $table->string('return_condition')->nullable(); // Condition when returned
            $table->string('status'); // 'Issued', 'Returned', 'Lost', 'Damaged', 'Replaced'
            $table->text('usage_terms')->nullable(); // Terms and conditions
            $table->text('care_instructions')->nullable();
            $table->boolean('requires_training')->default(false);
            $table->date('training_completed')->nullable();
            $table->foreignId('issued_by')->constrained('users');
            $table->foreignId('received_by')->nullable()->constrained('users'); // Who received it back
            $table->decimal('damage_charges', 8, 2)->default(0);
            $table->decimal('replacement_cost', 10, 2)->nullable();
            $table->text('damage_description')->nullable();
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable(); // Receipt, photos, etc.
            $table->timestamps();

            $table->index(['employee_id', 'status']);
            $table->index(['item_type', 'status']);
            $table->index('covenant_number');
            $table->index('issued_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_covenants');
    }
};
