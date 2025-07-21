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
        Schema::create('bosla_gomrok', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('Unique bosla code');
            $table->string('name')->comment('Bosla name/title');
            $table->enum('document_type', ['Export', 'Import', 'Transit', 'Temporary'])
                ->comment('Type of customs document');
            $table->string('customs_office')->comment('Customs office name');
            $table->integer('processing_hours')->comment('Processing time in hours');
            $table->decimal('cost', 10, 2)->default(0)->comment('Cost in USD');
            $table->text('description')->nullable()->comment('Description of the bosla');
            $table->text('required_documents')->nullable()->comment('Required documents list');
            $table->integer('validity_days')->nullable()->comment('Validity period in days, null = no expiry');
            $table->boolean('is_mandatory')->default(false)->comment('Whether this bosla is mandatory');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();

            // Indexes
            $table->index('code');
            $table->index('status');
            $table->index('document_type');
            $table->index('customs_office');
            $table->index(['status', 'document_type']);
            $table->index(['status', 'customs_office']);
            $table->index(['document_type', 'customs_office']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bosla_gomrok');
    }
};
