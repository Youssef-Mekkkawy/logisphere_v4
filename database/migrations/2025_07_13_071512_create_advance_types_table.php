<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advance_types', function (Blueprint $table) {
            $table->id();
            $table->string('type_code')->unique();
            $table->string('type_name');
            $table->text('description')->nullable();
            $table->string('advance_category'); // 'Salary', 'Travel', 'Emergency', 'Project', 'Medical', 'Training'
            $table->decimal('max_amount', 12, 2)->nullable(); // Maximum advance amount
            $table->decimal('max_percentage', 5, 2)->nullable(); // Max % of salary
            $table->integer('max_repayment_months')->default(12); // Max months to repay
            $table->boolean('requires_approval')->default(true);
            $table->string('approval_level')->nullable(); // 'Manager', 'HR', 'Finance', 'CEO'
            $table->json('required_documents')->nullable(); // Documents needed
            $table->text('eligibility_criteria')->nullable();
            $table->decimal('interest_rate', 5, 2)->default(0); // Interest rate if applicable
            $table->string('repayment_method')->default('Monthly'); // 'Monthly', 'Lump Sum', 'Flexible'
            $table->boolean('auto_deduct_salary')->default(true);
            $table->integer('minimum_service_months')->nullable(); // Min months of service required
            $table->integer('cooling_period_months')->nullable(); // Months before next advance
            $table->boolean('affects_gratuity')->default(false); // Impact on end of service
            $table->foreignId('account_id')->nullable()->constrained(); // GL account
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['advance_category', 'is_active']);
            $table->index('type_code');
            $table->index('requires_approval');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advance_types');
    }
};
