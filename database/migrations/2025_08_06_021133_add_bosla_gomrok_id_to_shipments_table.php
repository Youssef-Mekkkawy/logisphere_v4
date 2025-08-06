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
        Schema::table('shipments', function (Blueprint $table) {
            // Add bosla_gomrok_id foreign key column
            $table->foreignId('bosla_gomrok_id')->nullable()->after('shipment_type_id')->constrained('bosla_gomrok')->nullOnDelete();

            // Add index for better performance
            $table->index(['bosla_gomrok_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            // Drop foreign key and index
            $table->dropForeign(['bosla_gomrok_id']);
            $table->dropIndex(['bosla_gomrok_id', 'status']);
            $table->dropColumn('bosla_gomrok_id');
        });
    }
};
