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
        Schema::create('employee_advances', function (Blueprint $table) {
            $table->id();
            $table->string('advance_number')->unique();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['salary', 'travel', 'emergency', 'project', 'other']);
            $table->decimal('amount', 15, 2);
            $table->decimal('repaid_amount', 15, 2)->default(0);
            $table->decimal('balance', 15, 2);
            $table->date('issued_date');
            $table->date('due_date')->nullable();
            $table->enum('status', ['issued', 'partially_repaid', 'fully_repaid', 'written_off']);
            $table->text('reason');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_advances');
    }
};
