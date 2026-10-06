<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Assessment;
use App\Models\AssessmentAnswer;
use App\Models\Question;
use App\Models\Choice;
use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssessmentTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create(['role' => 'learner']);
    }

    public function test_can_create_assessment(): void
    {
        $assessment = Assessment::create([
            'user_id' => $this->user->id,
            'source_package_name' => 'Practice Exam 1',
            'assessment_type' => 'full_exam',
            'total_questions' => 10,
            'status' => 'in_progress',
        ]);

        $this->assertDatabaseHas('assessments', [
            'id' => $assessment->id,
            'user_id' => $this->user->id,
            'source_package_name' => 'Practice Exam 1',
        ]);
    }

    public function test_score_calculation_is_accurate(): void
    {
        $assessment = Assessment::create([
            'user_id' => $this->user->id,
            'total_questions' => 10,
            'score_percentage' => 80.00,
        ]);

        $this->assertEquals(80.00, $assessment->score_percentage);
        $this->assertTrue($assessment->passThreshold());
    }

    public function test_time_tracking_works_correctly(): void
    {
        $startTime = now();
        $assessment = Assessment::create([
            'user_id' => $this->user->id,
            'started_at' => $startTime,
            'submitted_at' => $startTime->copy()->addMinutes(30),
            'time_spent_seconds' => 1800,
        ]);

        $expectedTime = 30 * 60;
        $actualTime = $assessment->timeSpentSeconds();

        $this->assertEquals($expectedTime, $actualTime);
    }

    public function test_pass_threshold_60_percent(): void
    {
        $passedAssessment = Assessment::create([
            'user_id' => $this->user->id,
            'score_percentage' => 60.00,
        ]);
        
        $justBelowThreshold = Assessment::create([
            'user_id' => $this->user->id,
            'score_percentage' => 59.99,
        ]);

        $this->assertTrue($passedAssessment->passThreshold());
        $this->assertFalse($justBelowThreshold->passThreshold());
    }

    public function test_topic_performance_aggregation(): void
    {
        $topic = Topic::factory()->create();
        $question = Question::factory()->create(['topic_id' => $topic->id]);
        
        $assessment = Assessment::create([
            'user_id' => $this->user->id,
            'total_questions' => 1,
            'status' => 'submitted',
        ]);

        AssessmentAnswer::create([
            'assessment_id' => $assessment->id,
            'question_id' => $question->id,
            'selected_answer_code' => 'A',
            'is_correct' => true,
        ]);

        $performance = $assessment->topic_performance;
        $this->assertNotEmpty($performance);
        $this->assertEquals($topic->id, $performance[0]['topic_id']);
        $this->assertEquals(1, $performance[0]['correct']);
    }
}
