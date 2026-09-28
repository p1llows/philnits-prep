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
        Schema::create('mistakes', function (Blueprint $table) {
            $table->id();
            
            // User and question reference
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->foreignId('assessment_id')->nullable()->constrained('assessments')->onDelete('cascade');
            
            // Mistake details
            $table->string('selected_answer_code'); // What they chose
            $table->string('correct_answer_code'); // What should have been chosen
            
            // Attempt tracking
            $table->integer('attempt_number')->default(1); // First mistake, second time wrong = 2, etc.
            $table->timestamp('first_mistaken_at');
            $table->timestamp('last_mistaken_at');
            
            // Resolution tracking
            $table->boolean('is_resolved')->default(false); // Corrected on later attempt
            $table->timestamp('resolved_at')->nullable();
            $table->integer('attempts_to_resolve')->default(0);
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'is_resolved']);
            $table->index(['user_id', 'first_mistaken_at']);
            $table->unique(['user_id', 'question_id'], 'user_question_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mistakes');
    }
};
