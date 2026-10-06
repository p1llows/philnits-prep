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
        Schema::create('source_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "PhilNITS IP Passport Exam 2024 Q1"
            $table->text('description')->nullable();
            
            // Document files tracking
            $table->string('questions_file_path')->nullable(); // Path to Questions PDF
            $table->string('answers_file_path')->nullable(); // Path to Answers PDF
            $table->string('questions_file_hash')->nullable(); // For duplicate detection
            $table->string('answers_file_hash')->nullable();
            
            // Import status tracking
            $table->enum('status', [
                'draft', 
                'importing', 
                'imported', 
                'validating', 
                'validation_failed', 
                'pending_review', 
                'approved', 
                'published', 
                'archived'
            ])->default('draft');
            
            // Statistics
            $table->integer('question_count')->default(0);
            $table->integer('answer_count')->default(0);
            $table->integer('validated_count')->default(0);
            $table->integer('published_count')->default(0);
            
            // Attribution
            $table->string('source_name')->nullable(); // Original exam source name
            $table->string('source_date')->nullable(); // Date of original exam
            $table->string('attribution_note')->nullable(); // Citation info
            
            // Audit trail
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('imported_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('published_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('imported_at')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('status');
            $table->index('questions_file_hash');
            $table->index('answers_file_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('source_packages');
    }
};
