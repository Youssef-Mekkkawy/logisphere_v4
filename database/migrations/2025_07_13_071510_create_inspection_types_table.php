<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspection_types', function (Blueprint $table) {
            $table->id();
            $table->string('inspection_code')->unique();
            $table->string('inspection_name');
            $table->string('inspection_category'); // 'Pre-shipment', 'Quality', 'Quantity', 'Loading', 'Security'
            $table->text('description')->nullable();
            $table->string('inspection_authority')->nullable(); // Who performs inspection
            $table->string('certificate_type')->nullable(); // Type of certificate issued
            $table->decimal('inspection_fee', 8, 2)->nullable();
            $table->integer('inspection_duration_hours')->default(4); // How long inspection takes
            $table->boolean('requires_advance_notice')->default(true);
            $table->integer('notice_period_hours')->default(24);
            $table->json('required_documents')->nullable(); // Docs needed for inspection
            $table->json('inspection_criteria')->nullable(); // What gets inspected
            $table->text('special_requirements')->nullable();
            $table->boolean('is_mandatory')->default(false); // Required by law/regulation
            $table->string('applicable_cargo_types')->nullable(); // Which cargo types need this
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['inspection_category', 'is_active']);
            $table->index('inspection_code');
            $table->index('is_mandatory');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspection_types');
    }
};
