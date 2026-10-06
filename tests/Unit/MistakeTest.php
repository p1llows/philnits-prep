<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Question;
use App\Models\Mistake;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MistakeTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'learner']);
    }

    public function test_mistake_recording_creates_record(): void
    {
        $question = Question::factory()->create();
        
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
        $question = Question::factory()->create();
        
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
        $question = Question::factory()->create();
        
        Mistake::create([
            'user_id' => $this->user->id,
            'question_id' => $question->id,
            'selected_answer_code' => 'A',
            'correct_answer_code' => 'B',
        ]);

        $this->expectException(\Illuminate\Database\UniqueConstraintViolationException::class);

        Mistake::create([
            'user_id' => $this->user->id,
            'question_id' => $question->id,
            'selected_answer_code' => 'C',
            'correct_answer_code' => 'B',
        ]);
    }
}
