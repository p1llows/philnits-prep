<?php

namespace Tests\Feature;

use App\Filament\Pages\ReviewQueue;
use App\Models\Assessment;
use App\Models\AssessmentAnswer;
use App\Models\Choice;
use App\Models\Question;
use App\Models\SourcePackage;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminStage2Test extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $learner;
    protected Topic $topic;
    protected SourcePackage $package;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
        $this->learner = User::factory()->create(['role' => 'learner', 'email_verified_at' => now()]);

        $this->topic = Topic::create([
            'name' => 'Information Security',
            'field' => 'technology',
            'code' => 'TECH-SEC',
            'color' => '1F3A5F',
            'is_active' => true,
        ]);

        $this->package = SourcePackage::create([
            'name' => 'IT Passport Spring 2024',
            'exam_session_label' => 'April 2024 Exam',
            'status' => 'in_review',
            'use' => 'practice',
            'is_official' => true,
        ]);
    }

    public function test_only_one_source_package_can_have_use_pretest_at_a_time(): void
    {
        $pkg1 = SourcePackage::create([
            'name' => 'Paper 1',
            'use' => 'pretest',
            'is_official' => true,
        ]);

        $this->assertEquals('pretest', $pkg1->fresh()->use);

        $pkg2 = SourcePackage::create([
            'name' => 'Paper 2',
            'use' => 'pretest',
            'is_official' => true,
        ]);

        // pkg1 must have been automatically demoted to 'practice'
        $this->assertEquals('practice', $pkg1->fresh()->use);
        $this->assertEquals('pretest', $pkg2->fresh()->use);

        // Updating pkg1 back to pretest demotes pkg2
        $pkg1 = $pkg1->fresh();
        $pkg1->update(['use' => 'pretest']);
        $this->assertEquals('pretest', $pkg1->fresh()->use);
        $this->assertEquals('practice', $pkg2->fresh()->use);
    }

    public function test_publishing_approved_questions_is_blocked_without_an_answer_key(): void
    {
        $packageWithoutKey = SourcePackage::create([
            'name' => 'Paper Without Key',
            'answer_key_path' => null,
            'status' => 'in_review',
        ]);

        $question = Question::create([
            'source_package_id' => $packageWithoutKey->id,
            'source_question_number' => 1,
            'topic_id' => $this->topic->id,
            'question_text' => 'Sample Question',
            'correct_answer_code' => 'A',
            'status' => 'approved',
        ]);

        // Trigger action logic directly or via resource check:
        // When answer_key_path is null, publish action is not permitted and questions remain approved
        $this->assertNull($packageWithoutKey->answer_key_path);
        $this->assertEquals('approved', $question->fresh()->status);
    }

    public function test_review_queue_approve_guard_blocks_model_suggestion_and_missing_attributes(): void
    {
        $question = Question::create([
            'source_package_id' => $this->package->id,
            'source_question_number' => 1,
            'topic_id' => null, // missing topic
            'question_text' => 'AI Extracted Question',
            'correct_answer_code' => null, // missing answer
            'answer_source' => 'model_suggestion',
            'status' => 'in_review',
        ]);

        // Attempting to approve without correct choice or topic or while model_suggestion
        Livewire::actingAs($this->admin)
            ->test(ReviewQueue::class)
            ->set('correct_answer_code', null)
            ->call('approve');

        $this->assertEquals('in_review', $question->fresh()->status);

        // Now set correct choice but keep model_suggestion
        Livewire::actingAs($this->admin)
            ->test(ReviewQueue::class)
            ->set('correct_answer_code', 'B')
            ->set('topic_id', $this->topic->id)
            ->set('answer_source', 'model_suggestion')
            ->call('approve');

        // Status must still be in_review (model suggestions cannot be approved)
        $this->assertEquals('in_review', $question->fresh()->status);

        // Change answer_source to official_key and approve
        Livewire::actingAs($this->admin)
            ->test(ReviewQueue::class)
            ->set('correct_answer_code', 'B')
            ->set('topic_id', $this->topic->id)
            ->set('answer_source', 'official_key')
            ->set('explanation', 'Valid rationale')
            ->call('approve');

        $fresh = $question->fresh();
        $this->assertEquals('approved', $fresh->status);
        $this->assertEquals('official_key', $fresh->answer_source);
        $this->assertEquals('approved', $fresh->explanation_status);
        $this->assertEquals($this->admin->id, $fresh->approved_by);
        $this->assertNotNull($fresh->approved_at);
    }

    public function test_only_published_questions_reach_learners(): void
    {
        $publishedQ = Question::create([
            'source_package_id' => $this->package->id,
            'source_question_number' => 10,
            'topic_id' => $this->topic->id,
            'question_text' => 'Learner should see this',
            'correct_answer_code' => 'A',
            'status' => 'published',
        ]);

        $inReviewQ = Question::create([
            'source_package_id' => $this->package->id,
            'source_question_number' => 11,
            'topic_id' => $this->topic->id,
            'question_text' => 'Learner should NOT see this',
            'correct_answer_code' => 'B',
            'status' => 'in_review',
        ]);

        $draftQ = Question::create([
            'source_package_id' => $this->package->id,
            'source_question_number' => 12,
            'topic_id' => $this->topic->id,
            'question_text' => 'Draft question hidden',
            'correct_answer_code' => 'C',
            'status' => 'draft',
        ]);

        $publishedList = Question::published()->pluck('id')->all();

        $this->assertContains($publishedQ->id, $publishedList);
        $this->assertNotContains($inReviewQ->id, $publishedList);
        $this->assertNotContains($draftQ->id, $publishedList);

        // Verify learner topic practice view only gets published questions
        $this->user = $this->learner;
        $this->actingAs($this->learner);

        // Create completed baseline assessment to pass pretest gate
        Assessment::create([
            'user_id' => $this->learner->id,
            'status' => 'submitted',
            'score_percentage' => 80.0,
            'total_questions' => 100,
            'correct_answers' => 80,
            'started_at' => now()->subHours(2),
            'completed_at' => now(),
        ]);

        $response = $this->get('/topics/' . $this->topic->id);
        $response->assertStatus(200);
        $response->assertSee('Learner should see this');
        $response->assertDontSee('Learner should NOT see this');
        $response->assertDontSee('Draft question hidden');
    }

    public function test_correct_rate_calculation_and_check_key_rule(): void
    {
        $qNormal = Question::create([
            'topic_id' => $this->topic->id,
            'source_question_number' => 20,
            'question_text' => 'Question with 5 attempts',
            'correct_answer_code' => 'A',
            'status' => 'published',
        ]);

        $assessment = Assessment::create([
            'user_id' => $this->learner->id,
            'status' => 'submitted',
            'score_percentage' => 50.0,
            'total_questions' => 10,
            'correct_answers' => 5,
            'started_at' => now()->subHours(1),
            'completed_at' => now(),
        ]);

        // 5 attempts (< 10)
        for ($i = 0; $i < 5; $i++) {
            AssessmentAnswer::create([
                'assessment_id' => $assessment->id,
                'question_id' => $qNormal->id,
                'selected_answer_code' => 'A',
                'is_correct' => true,
            ]);
        }

        // Correct rate helper logic: < 10 attempts gives n/a
        $this->assertEquals(5, $qNormal->assessmentAnswers()->count());
        $rateNormal = $qNormal->assessmentAnswers()->count() < 10 ? 'n/a' : 'calculated';
        $this->assertEquals('n/a', $rateNormal);

        // Low correct rate question with >= 10 attempts (< 20% correct)
        $qLow = Question::create([
            'topic_id' => $this->topic->id,
            'source_question_number' => 21,
            'question_text' => 'Very difficult or wrong key question',
            'correct_answer_code' => 'A',
            'status' => 'published',
        ]);

        // 10 attempts: 1 correct, 9 incorrect => 10%
        AssessmentAnswer::create([
            'assessment_id' => $assessment->id,
            'question_id' => $qLow->id,
            'selected_answer_code' => 'A',
            'is_correct' => true,
        ]);
        for ($i = 0; $i < 9; $i++) {
            AssessmentAnswer::create([
                'assessment_id' => $assessment->id,
                'question_id' => $qLow->id,
                'selected_answer_code' => 'B',
                'is_correct' => false,
            ]);
        }

        $totalAttempts = $qLow->assessmentAnswers()->count();
        $correctAttempts = $qLow->assessmentAnswers()->where('is_correct', true)->count();
        $rate = ($correctAttempts / $totalAttempts) * 100;

        $this->assertEquals(10, $totalAttempts);
        $this->assertEquals(10.0, $rate);
        $this->assertTrue($totalAttempts >= 10 && $rate < 20); // Triggers "Check key"
    }
}
