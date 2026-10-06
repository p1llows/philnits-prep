<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Livewire\StudyPlanner;
use App\Livewire\StudyGoals;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class StudyPlanningFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'learner']);
        $this->actingAs($this->user);
    }

    public function test_study_planner_requires_authentication(): void
    {
        Auth::logout();
        $response = $this->get('/planner');
        
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_planner(): void
    {
        $response = $this->get('/planner');
        
        $response->assertStatus(200);
    }

    public function test_can_create_study_schedule(): void
    {
        Livewire::test(StudyPlanner::class)
            ->set('scheduleTitle', 'My Study Plan')
            ->set('startDate', now()->format('Y-m-d'))
            ->set('weeklyStudyHours', 10)
            ->set('intensity', 'moderate')
            ->set('selectedDays', ['mon', 'tue', 'wed'])
            ->call('saveSchedule');

        $this->assertDatabaseHas('study_schedules', [
            'user_id' => $this->user->id,
            'title' => 'My Study Plan',
        ]);
    }

    public function test_can_create_study_goal(): void
    {
        Livewire::test(StudyGoals::class)
            ->set('goalTitle', 'Achieve 80% Score')
            ->set('targetDate', now()->addMonths(3)->format('Y-m-d'))
            ->set('goalType', 'score_target')
            ->set('targetValue', 80)
            ->call('saveGoal');

        $this->assertDatabaseHas('study_goals', [
            'user_id' => $this->user->id,
            'title' => 'Achieve 80% Score',
        ]);
    }
}
