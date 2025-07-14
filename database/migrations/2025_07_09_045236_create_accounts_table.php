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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_code')->unique();
            $table->string('account_name');
            $table->string('account_type'); // 'asset', 'liability', 'equity', 'revenue', 'expense'
            $table->string('account_category'); // 'cash', 'bank', 'receivables', 'payables', etc.
            $table->unsignedBigInteger('parent_account_id')->nullable();
            $table->integer('account_level')->default(1);
            $table->string('account_path')->nullable(); // For hierarchical queries
            $table->text('description')->nullable();
            $table->string('currency', 3)->default('USD');
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_system')->default(false); // System accounts cannot be deleted
            $table->boolean('allow_manual_entry')->default(true);
            $table->json('account_rules')->nullable(); // Validation rules
            $table->timestamps();

            $table->foreign('parent_account_id')->references('id')->on('accounts')->onDelete('set null');
            $table->index(['account_type', 'is_active']);
            $table->index('account_category');
            $table->index('account_path');
        });
    }

    public function down()
    {
        Schema::dropIfExists('accounts');
    }
};
