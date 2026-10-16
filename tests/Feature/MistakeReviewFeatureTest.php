<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Question;
use App\Models\Choice;
use App\Models\Mistake;
use App\Models\Topic;
use App\Models\Assessment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Livewire\MistakeReview;

class MistakeReviewFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_mistake_review_renders_correctly_with_mistakes(): void
    {
        $user = User::factory()->create([
            'role' => 'learner',
            'email_verified_at' => now(),
        ]);

        // Complete pretest requirement
        Assessment::factory()->create([
            'user_id' => $user->id,
            'status' => 'submitted',
            'score_percentage' => 75,
        ]);

        $topic = Topic::factory()->create(['name' => 'Algorithms']);
        $question = Question::factory()->create([
            'topic_id' => $topic->id,
            'question_text' => 'What is the time complexity of binary search?',
            'explanation' => 'Binary search runs in O(log n) time.',
        ]);

        Choice::create([
            'question_id' => $question->id,
            'code' => 'A',
            'text' => 'O(1)',
            'is_correct' => false,
            'display_order' => 1,
        ]);

        Choice::create([
            'question_id' => $question->id,
            'code' => 'B',
            'text' => 'O(log n)',
            'is_correct' => true,
            'display_order' => 2,
        ]);

        Mistake::create([
            'user_id' => $user->id,
            'question_id' => $question->id,
            'selected_answer_code' => 'A',
            'correct_answer_code' => 'B',
            'attempt_number' => 1,
            'is_resolved' => false,
        ]);

        $response = $this->actingAs($user)->get('/mistakes');
        $response->assertStatus(200);
        $response->assertSee('What is the time complexity of binary search?');

        Livewire::actingAs($user)
            ->test(MistakeReview::class)
            ->assertSee('What is the time complexity of binary search?')
            ->call('selectAnswer', 'B')
            ->assertSet('isCorrect', true);
    }
}
