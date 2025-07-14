<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('all_status', function (Blueprint $table) {
            $table->id();
            $table->string('module'); // 'shipment', 'employee', 'company', 'invoice', etc.
            $table->string('status_code')->unique();
            $table->string('status_name');
            $table->string('display_name');
            $table->string('color', 7)->default('#6b7280'); // Hex color for UI
            $table->string('icon')->nullable(); // Icon class or emoji
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_final')->default(false); // Cannot be changed once set
            $table->json('allowed_transitions')->nullable(); // Next possible statuses
            $table->json('permissions')->nullable(); // Who can set this status
            $table->timestamps();

            $table->index(['module', 'is_active']);
            $table->index('status_code');
        });
    }

    public function down()
    {
        Schema::dropIfExists('all_status');
    }
};
