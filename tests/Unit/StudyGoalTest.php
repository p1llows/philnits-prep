<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\StudyGoal;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudyGoalTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'learner']);
    }

    public function test_goal_progress_percentage_calculates(): void
    {
        $goal = StudyGoal::create([
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
        $goal = StudyGoal::create([
            'user_id' => $this->user->id,
            'title' => 'Complete 10 Assessments',
            'type' => 'assessment_count',
            'target_value' => 10,
            'current_value' => 10,
            'target_date' => now()->addMonths(1),
            'is_achieved' => false,
        ]);

        $goal->updateCurrentValue(1);
        
        $this->assertTrue($goal->refresh()->is_achieved);
        $this->assertNotNull($goal->achieved_at);
    }

    public function test_overdue_goal_detection(): void
    {
        $overdueGoal = StudyGoal::create([
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
        $approachingGoal = StudyGoal::create([
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
