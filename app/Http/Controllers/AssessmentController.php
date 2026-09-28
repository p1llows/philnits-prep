<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    /**
     * Display the initial assessment or continue existing assessment.
     */
    public function index(Request $request)
    {
        // Check if user has an active assessment
        $assessment = Assessment::where('user_id', auth()->id())
            ->whereIn('status', ['in_progress'])
            ->orderBy('created_at', 'desc')
            ->first();

        if ($assessment && $assessment->isInProgress()) {
            return redirect()->route('assessment.show', $assessment->id);
        }

        // Create new initial assessment
        $questions = $this->getQuestionsForInitialAssessment();
        
        $assessment = Assessment::create([
            'user_id' => auth()->id(),
            'assessment_type' => 'initial',
            'total_questions' => count($questions),
            'question_selection' => 'all',
            'started_at' => now(),
            'status' => 'in_progress',
        ]);

        return redirect()->route('assessment.show', $assessment->id);
    }

    /**
     * Display a specific assessment.
     */
    public function show(Assessment $assessment)
    {
        // Ensure user owns this assessment
        if ($assessment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this assessment');
        }

        // Get questions with choices
        $questions = $assessment->answers()
            ->with(['question.choices'])
            ->pluck('question_id')
            ->toArray();

        if (empty($questions)) {
            $questions = $this->getQuestionsForAssessment($assessment);
        } else {
            $questions = $questions;
        }

        return view('assessments.show', [
            'assessment' => $assessment,
            'questions' => $questions,
        ]);
    }

    /**
     * Submit an assessment for grading.
     */
    public function submit(Request $request, Assessment $assessment)
    {
        // Validate user ownership
        if ($assessment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        // Validate submission data
        $request->validate([
            'answers' => 'required|array|min:1',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.selected_answer' => 'required|string|max:1',
        ]);

        DB::beginTransaction();

        try {
            $answers = [];
            $correctCount = 0;
            $incorrectCount = 0;

            foreach ($request->input('answers') as $answerData) {
                $questionId = $answerData['question_id'];
                $selectedAnswer = strtoupper(trim($answerData['selected_answer']));

                // Get question details
                $question = $assessment->answers()
                    ->where('question_id', $questionId)
                    ->with('question')
                    ->first();

                if (!$question) {
                    $questionModel = \App\Models\Question::findOrFail($questionId);
                    $isCorrect = $questionModel->correct_answer_code === $selectedAnswer;
                    
                    \App\Models\AssessmentAnswer::create([
                        'assessment_id' => $assessment->id,
                        'question_id' => $questionId,
                        'selected_answer_code' => $selectedAnswer,
                        'is_correct' => $isCorrect,
                        'answered_at' => now(),
                    ]);
                } else {
                    // Update existing answer
                    $question->update([
                        'selected_answer_code' => $selectedAnswer,
                        'answered_at' => now(),
                    ]);
                    
                    $isCorrect = $question->is_correct = ($question->question->correct_answer_code === $selectedAnswer);
                }

                if ($isCorrect) {
                    $correctCount++;
                } else {
                    $incorrectCount++;
                    
                    // Record mistake if incorrect
                    $this->recordMistake($assessment->user_id, $questionId, $selectedAnswer, 
                        $question->question->correct_answer_code, $assessment->id);
                }

                $answers[] = [
                    'question_id' => $questionId,
                    'selected_answer' => $selectedAnswer,
                    'is_correct' => $isCorrect,
                ];
            }

            // Calculate score percentage
            $totalQuestions = count($answers);
            $scorePercentage = $totalQuestions > 0 
                ? round(($correctCount / $totalQuestions) * 100, 2)
                : 0;

            // Update assessment with results
            $assessment->update([
                'submitted_at' => now(),
                'time_spent_seconds' => $assessment->calculateTimeSpent(),
                'correct_count' => $correctCount,
                'incorrect_count' => $incorrectCount,
                'score_percentage' => $scorePercentage,
                'status' => 'graded',
            ]);

            DB::commit();

            return redirect()->route('assessment.results', $assessment->id)
                ->with('success', 'Assessment submitted successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Assessment submission failed', [
                'assessment_id' => $assessment->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to submit assessment. Please try again.');
        }
    }

    /**
     * Display assessment results with detailed analysis.
     */
    public function results(Assessment $assessment)
    {
        // Verify ownership
        if ($assessment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        // Load related data
        $assessment->load([
            'answers.question.choices',
            'answers.question.topic',
        ]);

        // Calculate topic performance
        $topicPerformance = $this->calculateTopicPerformance($assessment);

        // Identify weak areas (below 50% correct)
        $weakAreas = collect($topicPerformance)
            ->filter(fn($perf) => $perf['percentage'] < 50 && $perf['total'] > 0)
            ->sortBy('percentage')
            ->values();

        // Prepare recommended topics (topics that need improvement)
        $recommendedTopics = $weakAreas->take(3);

        return view('assessments.results', [
            'assessment' => $assessment,
            'topicPerformance' => $topicPerformance,
            'weakAreas' => $weakAreas,
            'recommendedTopics' => $recommendedTopics,
        ]);
    }

    /**
     * Get questions for initial assessment.
     */
    protected function getQuestionsForInitialAssessment(): array
    {
        // For initial assessment, get all published questions
        // In production, you might want to limit this or select strategically
        
        $questions = \App\Models\Question::published()
            ->with(['choices', 'topic'])
            ->get()
            ->toArray();

        return $questions;
    }

    /**
     * Get questions for a specific assessment type.
     */
    protected function getQuestionsForAssessment(Assessment $assessment): array
    {
        switch ($assessment->question_selection) {
            case 'topic_based':
                return \App\Models\Question::published()
                    ->where('topic_id', $assessment->selected_topic_id)
                    ->with(['choices', 'topic'])
                    ->get()
                    ->toArray();
            
            case 'random':
                return \App\Models\Question::published()
                    ->inRandomOrder()
                    ->limit($assessment->total_questions ?? 50)
                    ->with(['choices', 'topic'])
                    ->get()
                    ->toArray();
            
            default:
                // All questions
                return \App\Models\Question::published()
                    ->with(['choices', 'topic'])
                    ->get()
                    ->toArray();
        }
    }

    /**
     * Calculate topic-level performance for an assessment.
     */
    protected function calculateTopicPerformance(Assessment $assessment): array
    {
        $performance = [];
        
        $answers = $assessment->answers()
            ->with(['question.topic'])
            ->get();

        foreach ($answers as $answer) {
            if ($answer->question && $answer->question->topic) {
                $topic = $answer->question->topic;
                
                if (!isset($performance[$topic->id])) {
                    $performance[$topic->id] = [
                        'topic_id' => $topic->id,
                        'topic_name' => $topic->name,
                        'color' => $topic->color,
                        'total' => 0,
                        'correct' => 0,
                        'incorrect' => 0,
                        'percentage' => 0,
                    ];
                }
                
                $performance[$topic->id]['total']++;
                
                if ($answer->is_correct) {
                    $performance[$topic->id]['correct']++;
                } else {
                    $performance[$topic->id]['incorrect']++;
                }
            }
        }

        // Calculate percentages
        foreach ($performance as &$stats) {
            if ($stats['total'] > 0) {
                $stats['percentage'] = round(
                    ($stats['correct'] / $stats['total']) * 100, 
                    1
                );
            }
        }

        return array_values($performance);
    }

    /**
     * Record a mistake when user answers incorrectly.
     */
    protected function recordMistake(int $userId, int $questionId, string $wrongAnswer, 
        string $correctAnswer, int $assessmentId): void
    {
        // Check if mistake already exists
        $existingMistake = \App\Models\Mistake::where('user_id', $userId)
            ->where('question_id', $questionId)
            ->first();

        if ($existingMistake) {
            // Increment attempt number
            $existingMistake->incrementAttempt();
        } else {
            // Create new mistake record
            \App\Models\Mistake::create([
                'user_id' => $userId,
                'question_id' => $questionId,
                'assessment_id' => $assessmentId,
                'selected_answer_code' => $wrongAnswer,
                'correct_answer_code' => $correctAnswer,
                'attempt_number' => 1,
                'first_mistaken_at' => now(),
                'last_mistaken_at' => now(),
            ]);
        }
    }

    /**
     * List all assessments for the current user.
     */
    public function history(Request $request)
    {
        $assessments = Assessment::where('user_id', auth()->id())
            ->with(['answers.question.topic'])
            ->oldestFirst()
            ->paginate(20);

        return view('assessments.history', [
            'assessments' => $assessments,
        ]);
    }

    /**
     * Continue/resume an incomplete assessment.
     */
    public function resume(Assessment $assessment)
    {
        if ($assessment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if ($assessment->status !== 'in_progress') {
            return redirect()->route('assessment.results', $assessment->id);
        }

        return redirect()->route('assessment.show', $assessment->id);
    }
}
