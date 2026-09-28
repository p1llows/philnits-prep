<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;
use App\Models\Assessment;
use App\Models\Question;
use App\Models\Mistake;

class AssessmentTest extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test user
        $this->user = User::factory()->create(['role' => 'learner']);
    }

    public function test_can_create_assessment(): void
    {
        $assessment = Assessment::create([
            'user_id' => $this->user->id,
            'title' => 'Practice Exam 1',
            'total_questions' => 10,
            'status' => 'in_progress',
        ]);

        $this->assertDatabaseHas('assessments', [
            'id' => $assessment->id,
            'user_id' => $this->user->id,
            'title' => 'Practice Exam 1',
        ]);
    }

    public function test_score_calculation_is_accurate(): void
    {
        $assessment = Assessment::create([
            'user_id' => $this->user->id,
            'total_questions' => 10,
            'score' => 80.00,
        ]);

        $this->assertEquals(80.00, $assessment->score);
        $this->assertTrue($assessment->passThreshold()); // Assuming 60% pass
    }

    public function test_time_tracking_works_correctly(): void
    {
        $startTime = now();
        $assessment = Assessment::create([
            'user_id' => $this->user->id,
            'started_at' => $startTime,
            'completed_at' => $startTime->addMinutes(30),
        ]);

        $expectedTime = 30 * 60; // 30 minutes in seconds
        $actualTime = $assessment->timeSpentSeconds();

        $this->assertEquals($expectedTime, $actualTime);
    }

    public function test_pass_threshold_60_percent(): void
    {
        $passedAssessment = Assessment::create([
            'user_id' => $this->user->id,
            'score' => 60.00,
        ]);
        
        $justBelowThreshold = Assessment::create([
            'user_id' => $this->user->id,
            'score' => 59.99,
        ]);

        $this->assertTrue($passedAssessment->passThreshold());
        $this->assertFalse($justBelowThreshold->passThreshold());
    }

    public function test_topic_performance_aggregation(): void
    {
        $topic = \App\Models\Topic::factory()->create();
        $questions = Question::factory()->count(5)->create(['topic_id' => $topic->id]);
        
        $assessment = Assessment::create([
            'user_id' => $this->user->id,
            'topic_performance' => [
                $topic->id => [
                    'topic_name' => $topic->name,
                    'question_count' => 5,
                    'correct_count' => 4,
                    'score' => 80.00,
                ],
            ],
        ]);

        $this->assertArrayHasKey($topic->id, $assessment->topic_performance);
        $this->assertEquals(80.00, $assessment->topic_performance[$topic->id]['score']);
    }
}

class MistakeTest extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'learner']);
    }

    public function test_mistake_recording_creates_record(): void
    {
        $question = \App\Models\Question::factory()->create();
        
        $mistake = Mistake::create([
            'user_id' => $this->user->id,
            'question_id' => $question->id,
            'selected_answer_code' => 'A',
            'correct_answer_code' => 'B',
            'attempt_number' => 1,
        ]);

        $this->assertDatabaseHas('mistakes', [
            'id' => $mistake->id,
            'user_id' => $this->user->id,
            'is_resolved' => false,
        ]);
    }

    public function test_attempt_number_increments_on_failure(): void
    {
        $question = \App\Models\Question::factory()->create();
        
        $mistake = Mistake::create([
            'user_id' => $this->user->id,
            'question_id' => $question->id,
            'selected_answer_code' => 'A',
            'correct_answer_code' => 'B',
            'attempt_number' => 1,
        ]);

        $mistake->incrementAttempt();
        
        $this->assertEquals(2, $mistake->refresh()->attempt_number);
    }

    public function test_unique_constraint_per_user_question(): void
    {
        $question = \App\Models\Question::factory()->create();
        
        Mistake::create([
            'user_id' => $this->user->id,
            'question_id' => $question->id,
            'selected_answer_code' => 'A',
            'correct_answer_code' => 'B',
        ]);

        // This should fail due to unique constraint
        $duplicate = Mistake::create([
            'user_id' => $this->user->id,
            'question_id' => $question->id,
            'selected_answer_code' => 'C',
            'correct_answer_code' => 'B',
        ]);

        $this->assertNull($duplicate);
    }
}

class StudyGoalTest extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'learner']);
    }

    public function test_goal_progress_percentage_calculates(): void
    {
        $goal = \App\Models\StudyGoal::create([
            'user_id' => $this->user->id,
            'title' => 'Score 80%',
            'type' => 'score_target',
            'target_value' => 100,
            'current_value' => 75,
            'target_date' => now()->addMonths(2),
        ]);

        $this->assertEquals(75.0, $goal->progress_percentage);
    }

    public function test_goal_achievement_detection(): void
    {
        $goal = \App\Models\StudyGoal::create([
            'user_id' => $this->user->id,
            'title' => 'Complete 10 Assessments',
            'type' => 'assessment_count',
            'target_value' => 10,
            'current_value' => 10,
            'target_date' => now()->addMonths(1),
            'is_achieved' => false,
        ]);

        $goal->updateCurrentValue(1); // Attempt to increment past target
        
        $this->assertTrue($goal->refresh()->is_achieved);
        $this->assertNotNull($goal->achieved_at);
    }

    public function test_overdue_goal_detection(): void
    {
        $overdueGoal = \App\Models\StudyGoal::create([
            'user_id' => $this->user->id,
            'title' => 'Overdue Goal',
            'type' => 'score_target',
            'target_value' => 100,
            'current_value' => 50,
            'target_date' => now()->subDay(),
            'is_achieved' => false,
        ]);

        $this->assertTrue($overdueGoal->is_overdue);
    }

    public function test_approaching_deadline_detection(): void
    {
        $approachingGoal = \App\Models\StudyGoal::create([
            'user_id' => $this->user->id,
            'title' => 'Approaching Deadline',
            'type' => 'score_target',
            'target_value' => 100,
            'current_value' => 50,
            'target_date' => now()->addDays(5),
            'is_achieved' => false,
        ]);

        $this->assertTrue($approachingGoal->is_approaching_deadline(7));
    }
}
