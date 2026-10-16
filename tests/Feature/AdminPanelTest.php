<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_admin(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_learner_receives_403_when_accessing_admin(): void
    {
        $learner = User::factory()->create([
            'role' => 'learner',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($learner)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_overview(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Paxi');
        $response->assertSee('paxi-mark.svg');
        $response->assertSee('Profile Settings');
        $response->assertSee('Log Out');
        $response->assertSee('Learner funnel');
        $response->assertSee('Overview');
    }

    public function test_admin_can_access_admin_profile_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get('/admin/profile');
        $response->assertStatus(200);
        $response->assertSee('Profile');
    }

    public function test_admin_can_access_pretest_and_rules_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get('/admin/pre-test-and-rules');
        $response->assertStatus(200);
        $response->assertSee('Baseline pre-test');
        $response->assertSee('Passing rules');
        $response->assertSee('Study roadmap');
    }

    public function test_admin_panel_navigation_groups_and_subheadings(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Overview');
        $response->assertSee('Content');
        $response->assertSee('People');
        $response->assertSee('Assessments');
        $response->assertSee('Insights');
        $response->assertSee('Configure');
        $response->assertSee('System');
        $response->assertSee('How Paxi is doing and what needs your attention.');
    }

    public function test_admin_can_access_analytics_and_review_queue(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get('/admin/analytics');
        $response->assertStatus(200);
        $response->assertSee('Analytics');
        $response->assertSee('Overall examination performance, field mastery comparisons, and difficulty diagnostics.');
        $response->assertSee('Completed pre-tests');
        $response->assertSee('Examination pass rate');
        $response->assertSee('Average score by field');
        $response->assertSee('Pre-test score spread');
        $response->assertSee('Hardest questions');

        $responseQueue = $this->actingAs($admin)->get('/admin/review-queue');
        $responseQueue->assertStatus(200);
        $responseQueue->assertSee('Review queue');
        $responseQueue->assertSee('Verify AI-extracted questions, confirm answer keys, and approve syllabus topics.');
    }

    public function test_dashboard_is_strictly_learner_facing_and_never_shows_admin_content(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
        // Learner dashboard components
        $response->assertSeeLivewire('assessment-dashboard');
        // Admin-only sections from the old admin dashboard must not be present
        $response->assertDontSee('System Control & Examination Management');
        $response->assertDontSee('Administrative Area');
    }
}
