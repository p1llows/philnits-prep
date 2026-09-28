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
        Schema::create('study_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('weekly_study_hours')->default(5); // Recommended baseline
            $table->enum('intensity', ['light', 'moderate', 'intense'])->default('moderate');
            $table->json('schedule_slots')->nullable(); // Specific days/times: [{"day": "mon", "time": "19:00-20:30"}]
            $table->boolean('is_active')->default(true);
            $table->boolean('is_committed')->default(false);
            $table->timestamp('committed_at')->nullable();
            $table->timestamps();
            
            // Indexes for common queries
            $table->index(['user_id', 'is_active']);
            $table->index(['user_id', 'is_committed']);
        });

        Schema::create('study_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('study_schedules')->onDelete('cascade');
            $table->foreignId('topic_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('question_id')->nullable()->constrained()->onDelete('set null');
            $table->string('session_type'); // practice, review_mistakes, full_assessment
            $table->date('scheduled_date');
            $table->time('scheduled_time')->nullable();
            $table->integer('planned_duration_minutes')->default(30);
            $table->enum('status', ['planned', 'completed', 'skipped'])->default('planned');
            $table->integer('actual_duration_minutes')->nullable();
            $table->float('score_percentage', 5, 2)->nullable(); // If assessment/practice done
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['schedule_id', 'scheduled_date']);
            $table->index(['status', 'scheduled_date']);
        });

        Schema::create('study_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('target_date');
            $table->enum('type', [
                'score_target', 
                'assessment_count', 
                'mistake_resolution', 
                'topic_mastery',
                'hours_studied'
            ]);
            $table->integer('target_value');
            $table->integer('current_value')->default(0);
            $table->boolean('is_achieved')->default(false);
            $table->timestamp('achieved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('study_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('schedule_id')->nullable()->constrained('study_schedules')->onDelete('set null');
            $table->foreignId('session_id')->nullable()->constrained('study_sessions')->onDelete('cascade');
            $table->string('reminder_type'); // session_start, daily_goal, weekly_review
            $table->time('reminder_time');
            $table->boolean('is_enabled')->default(true);
            $table->json('notification_preferences')->nullable(); // email, push, both
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_reminders');
        Schema::dropIfExists('study_goals');
        Schema::dropIfExists('study_sessions');
        Schema::dropIfExists('study_schedules');
    }
};
