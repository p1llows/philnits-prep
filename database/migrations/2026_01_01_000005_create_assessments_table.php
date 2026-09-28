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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            
            // User and session info
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('assessment_type')->default('initial'); // 'initial', 'practice', 'progress'
            $table->string('source_package_name')->nullable(); // Which exam this was based on
            
            // Question set metadata
            $table->integer('total_questions')->default(0);
            $table->enum('question_selection', ['all', 'random', 'topic_based'])->default('all');
            $table->integer('selected_topic_id')->nullable(); // If topic-based
            
            // Timing
            $table->timestamp('started_at');
            $table->timestamp('submitted_at')->nullable();
            $table->integer('time_spent_seconds')->default(0);
            
            // Results tracking
            $table->integer('correct_count')->default(0);
            $table->integer('incorrect_count')->default(0);
            $table->decimal('score_percentage', 5, 2)->nullable();
            
            // Status
            $table->enum('status', ['in_progress', 'submitted', 'graded'])->default('in_progress');
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'assessment_type']);
            $table->index('submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
