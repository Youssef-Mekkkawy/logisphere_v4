<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nationalities', function (Blueprint $table) {
            $table->id();
            $table->string('nationality_code', 3)->unique(); // ISO 3166-1 alpha-3
            $table->string('nationality_name');
            $table->string('country_code', 2); // ISO 3166-1 alpha-2
            $table->string('country_name');
            $table->string('flag_emoji', 10)->nullable();
            $table->string('language_code', 5)->nullable(); // ISO 639-1
            $table->string('currency_code', 3)->nullable(); // ISO 4217
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('nationality_code');
            $table->index('country_code');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('nationalities');
    }
};
