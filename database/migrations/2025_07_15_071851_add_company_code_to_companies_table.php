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
        Schema::table('companies', function (Blueprint $table) {
            // Add the missing columns
            $table->string('company_code', 20)->unique()->nullable()->after('id');
            $table->string('tax_number', 50)->unique()->nullable()->after('phone');
            $table->string('city', 100)->nullable()->after('country');
            $table->string('postal_code', 20)->nullable()->after('city');
            $table->string('website')->nullable()->after('postal_code');
            $table->decimal('credit_limit', 15, 2)->default(0)->after('service_type');
            $table->integer('payment_terms')->default(30)->after('credit_limit');
            $table->text('notes')->nullable()->after('payment_terms');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('notes');

            // Add indexes for better performance
            $table->index(['type', 'status']);
            $table->index(['country', 'status']);
            $table->index('company_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropIndex(['type', 'status']);
            $table->dropIndex(['country', 'status']);
            $table->dropIndex(['company_code']);

            $table->dropColumn([
                'company_code',
                'tax_number',
                'city',
                'postal_code',
                'website',
                'credit_limit',
                'payment_terms',
                'notes',
                'created_by'
            ]);
        });
    }
};
