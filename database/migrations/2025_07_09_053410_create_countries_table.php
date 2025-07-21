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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Country name');
            $table->string('code', 3)->unique()->nullable()->comment('Country code (ISO 3166-1 alpha-3)');
            $table->string('iso_code', 2)->unique()->nullable()->comment('ISO 3166-1 alpha-2 code');
            $table->string('phone_code', 10)->nullable()->comment('International dialing code');
            $table->string('currency', 3)->nullable()->comment('Currency code');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();

            // Indexes
            $table->index('name');
            $table->index('code');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
