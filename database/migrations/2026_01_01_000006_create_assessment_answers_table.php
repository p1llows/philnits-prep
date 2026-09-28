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
        Schema::create('assessment_answers', function (Blueprint $table) {
            $table->id();
            
            // Assessment and question reference
            $table->foreignId('assessment_id')->constrained('assessments')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            
            // User's answer
            $table->string('selected_answer_code'); // e.g., 'A', 'B', 'C', 'D'
            $table->boolean('is_correct');
            
            // Timing within assessment
            $table->timestamp('answered_at')->nullable();
            $table->integer('time_spent_seconds')->default(0);
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->index(['assessment_id', 'question_id']);
            $table->index(['assessment_id', 'is_correct']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_answers');
    }
};
