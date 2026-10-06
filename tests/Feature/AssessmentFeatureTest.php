<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Assessment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class AssessmentFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create(['role' => 'learner']);
        $this->actingAs($this->user);
    }

    public function test_assessment_page_requires_authentication(): void
    {
        Auth::logout();
        $response = $this->get('/assessments');
        
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_assessment_interface(): void
    {
        $assessment = Assessment::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'in_progress',
        ]);

        $response = $this->get('/assessment/' . $assessment->id);
        
        $response->assertStatus(200);
    }

    public function test_cannot_take_assessment_as_different_user(): void
    {
        $otherUser = User::factory()->create(['role' => 'learner']);
        $assessment = Assessment::create([
            'user_id' => $this->user->id,
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($otherUser)->get('/assessment/' . $assessment->id);
        
        $response->assertForbidden();
    }

    public function test_assessment_submission_validates_answers_server_side(): void
    {
        $this->markTestIncomplete('Full integration test needed');
    }
}
