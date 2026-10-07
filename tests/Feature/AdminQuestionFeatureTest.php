<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Topic;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminQuestionFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Topic $topic;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->topic = Topic::factory()->create(['name' => 'Database Systems', 'code' => 'DBS']);
    }

    public function test_admin_can_view_questions_list(): void
    {
        Question::factory()->create([
            'topic_id' => $this->topic->id,
            'question_text' => 'What is a Primary Key?',
            'status' => 'published',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/questions');
        $response->assertStatus(200);
        $response->assertSee('What is a Primary Key?');
    }

    public function test_admin_can_create_question_with_choices(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/questions', [
            'topic_id' => $this->topic->id,
            'question_text' => 'What does SQL stand for?',
            'difficulty' => 'easy',
            'status' => 'published',
            'correct_answer_code' => 'A',
            'explanation' => 'SQL stands for Structured Query Language.',
            'choices' => [
                'A' => 'Structured Query Language',
                'B' => 'Sequential Query Logic',
                'C' => 'System Quality Level',
                'D' => 'Server Question Language',
            ],
        ]);

        $response->assertRedirect('/admin/questions');
        $this->assertDatabaseHas('questions', [
            'question_text' => 'What does SQL stand for?',
            'correct_answer_code' => 'A',
        ]);
        $this->assertDatabaseHas('choices', [
            'code' => 'A',
            'text' => 'Structured Query Language',
        ]);
    }

    public function test_admin_can_toggle_publish_status(): void
    {
        $question = Question::factory()->create([
            'topic_id' => $this->topic->id,
            'status' => 'published',
        ]);

        $response = $this->actingAs($this->admin)->post('/admin/questions/' . $question->id . '/publish');
        $response->assertRedirect();
        
        $this->assertEquals('draft', $question->fresh()->status);
    }
}
