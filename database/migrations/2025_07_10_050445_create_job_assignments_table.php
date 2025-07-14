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
        Schema::create('job_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('job_number')->unique();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('shipment_id')->nullable()->constrained()->onDelete('set null');
            $table->string('task_description');
            $table->decimal('hours_worked', 8, 2)->default(0);
            $table->decimal('hourly_rate', 10, 2);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['assigned', 'in_progress', 'completed', 'billed'])->default('assigned');
            $table->boolean('is_billable')->default(true); // Can be billed to client
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_assignments');
    }
};
