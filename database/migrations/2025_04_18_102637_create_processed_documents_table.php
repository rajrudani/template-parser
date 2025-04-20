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
        Schema::create('processed_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_document_id')->constrained();
            $table->foreignId('school_id')->constrained();
            $table->foreignId('school_event_id')->nullable()->constrained();
            $table->string('filename');
            $table->string('file_path');
            $table->text('edited_content')->nullable(); // For storing edited version
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processed_documents');
    }
};
