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
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('nationality_id')->nullable()->constrained('nationalities')->after('email');
            $table->string('passport_number')->nullable()->after('nationality_id');
            $table->date('passport_expiry')->nullable()->after('passport_number');
            $table->string('visa_status')->nullable()->after('passport_expiry');
            $table->date('visa_expiry')->nullable()->after('visa_status');
            $table->string('employment_type')->default('Full-time')->after('status'); // Full-time, Part-time, Contract
            $table->date('contract_start')->nullable()->after('employment_type');
            $table->date('contract_end')->nullable()->after('contract_start');
            $table->string('manager_id')->nullable()->after('contract_end'); // Reports to
            $table->json('skills')->nullable()->after('manager_id'); // Array of skills
            $table->text('notes')->nullable()->after('skills');
        });
    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['nationality_id']);
            $table->dropColumn([
                'nationality_id',
                'passport_number',
                'passport_expiry',
                'visa_status',
                'visa_expiry',
                'employment_type',
                'contract_start',
                'contract_end',
                'manager_id',
                'skills',
                'notes'
            ]);
        });
    }
};
