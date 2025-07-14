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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('attachment_code')->unique();
            $table->morphs('attachable'); // Polymorphic relation (shipment_id, company_id, etc.)
            $table->string('file_name');
            $table->string('original_name');
            $table->string('file_path');
            $table->string('file_type'); // 'pdf', 'image', 'document', etc.
            $table->string('mime_type');
            $table->bigInteger('file_size'); // in bytes
            $table->string('category')->nullable(); // 'invoice', 'receipt', 'document', 'photo'
            $table->text('description')->nullable();
            $table->string('uploaded_by_type')->nullable(); // User, Employee, etc.
            $table->unsignedBigInteger('uploaded_by_id')->nullable();
            $table->boolean('is_public')->default(false);
            $table->boolean('is_required')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->json('metadata')->nullable(); // Additional file info
            $table->timestamps();

            $table->index(['attachable_type', 'attachable_id']);
            $table->index('category');
            $table->index('file_type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('attachments');
    }
};
