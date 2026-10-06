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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            
            // Source reference
            $table->foreignId('source_package_id')->nullable()->constrained('source_packages')->onDelete('set null');
            $table->integer('source_question_number')->comment('Question number from original PDF');
            $table->string('source_reference')->nullable(); // e.g., "Q1" or "Section 2, Q5"
            
            // Question content
            $table->text('question_text');
            $table->longText('explanation')->nullable(); // AI-generated or admin-approved explanation
            $table->enum('explanation_type', ['official', 'ai_generated', 'admin_created'])->default('official');
            $table->boolean('explanation_approved')->default(false);
            
            // Answer metadata
            $table->string('correct_answer_code'); // e.g., 'A', 'B', 'C', 'D'
            $table->enum('status', [
                'draft', 
                'imported', 
                'validated', 
                'pending_review', 
                'approved', 
                'published', 
                'archived'
            ])->default('draft');
            
            // Classification
            $table->foreignId('topic_id')->nullable()->constrained('topics')->onDelete('set null');
            $table->foreignId('suggested_topic_id')->nullable()->constrained('topics')->onDelete('set null'); // AI suggestion
            
            // Difficulty (optional, can be added later)
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->nullable();
            
            // Audit trail
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('published_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('explanation_created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('imported_at')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('explanation_created_at')->nullable();
            $table->timestamps();
            
            // Soft delete for archived questions
            $table->softDeletes();
            
            // Indexes for performance
            $table->index(['status', 'source_package_id']);
            $table->index('topic_id');
            $table->index('source_question_number');
            $table->index(['source_package_id', 'source_question_number']); // For duplicate detection
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
