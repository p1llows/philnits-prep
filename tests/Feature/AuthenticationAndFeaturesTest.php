<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use App\Models\User;
use App\Models\Assessment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('correct_password'),
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong_password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}

class AssessmentFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create(['role' => 'learner']);
        $this->actingAs($this->user);
    }

    public function test_assessment_page_requires_authentication(): void
    {
        $response = $this->get('/assessments');
        
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_assessment_interface(): void
    {
        $assessment = \App\Models\Assessment::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'available',
        ]);

        $response = $this->get('/assessments/' . $assessment->id);
        
        $response->assertStatus(200);
    }

    public function test_cannot_take_assessment_as_different_user(): void
    {
        $otherUser = User::factory()->create(['role' => 'learner']);
        $assessment = \App\Models\Assessment::create([
            'user_id' => $this->user->id,
            'status' => 'available',
        ]);

        $response = $this->actingAs($otherUser)->get('/assessments/' . $assessment->id);
        
        $response->assertForbidden();
    }

    public function test_assessment_submission_validates_answers_server_side(): void
    {
        // This would test that answers are validated server-side
        // preventing client manipulation
        $this->markTestIncomplete('Full integration test needed');
    }
}

class StudyPlanningFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'learner']);
        $this->actingAs($this->user);
    }

    public function test_study_planner_requires_authentication(): void
    {
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
        $response = $this->post('/api/planner/schedules', [
            'title' => 'My Study Plan',
            'start_date' => now()->toDateString(),
            'weekly_study_hours' => 10,
            'intensity' => 'moderate',
            'selected_days' => ['mon', 'tue', 'wed'],
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('study_schedules', [
            'user_id' => $this->user->id,
            'title' => 'My Study Plan',
        ]);
    }

    public function test_can_create_study_goal(): void
    {
        $response = $this->post('/api/planner/goals', [
            'title' => 'Achieve 80% Score',
            'target_date' => now()->addMonths(3)->toDateString(),
            'type' => 'score_target',
            'target_value' => 80,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('study_goals', [
            'user_id' => $this->user->id,
            'title' => 'Achieve 80% Score',
        ]);
    }
}
