<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Question;
use App\Models\Mistake;
use Illuminate\Support\Facades\Auth;

class PracticeMode extends Component
{
    public array $questions = [];
    public int $currentIndex = 0;
    public array $answers = []; // [question_id => selected_answer_code]
    public bool $showingFeedback = false;
    public bool $isCorrect = false;
    public ?Question $currentQuestion = null;
    public string $feedbackMessage = '';
    public int $correctCount = 0;
    public int $attemptNumber = 1;

    protected $listeners = ['nextQuestion', 'previousQuestion'];

    public function mount()
    {
        // Get questions from session or parameter
        $sessionData = session('practice_session');
        
        if (!$sessionData || !isset($sessionData['questions'])) {
            // Fallback: get recent practice or topic questions
            $this->loadQuestionsFromSession();
        } else {
            $this->questions = $sessionData['questions'];
            $this->currentIndex = $sessionData['current_index'] ?? 0;
            
            // Load previous answers for this session
            foreach ($this->questions as $index => $question) {
                if (isset($sessionData['answers'][$question['id']])) {
                    $this->answers[$question['id']] = $sessionData['answers'][$question['id']];
                }
            }
        }

        $this->currentIndex = max(0, min($this->currentIndex, count($this->questions) - 1));
        $this->loadCurrentQuestion();
    }

    protected function loadQuestionsFromSession()
    {
        // Default to getting all published questions or topic-based
        $topicId = request('topic');
        
        if ($topicId) {
            $questions = Question::where('topic_id', $topicId)
                ->published()
                ->with(['choices'])
                ->take(50) // Limit for practice
                ->get()
                ->toArray();
        } else {
            // Get random questions from all topics
            $questions = Question::published()
                ->with(['choices'])
                ->inRandomOrder()
                ->take(30)
                ->get()
                ->toArray();
        }
        
        $this->questions = $questions;
        
        session(['practice_session' => [
            'questions' => $this->questions,
            'current_index' => 0,
            'start_time' => now(),
            'practice_type' => 'random_practice',
        ]]);
    }

    public function getCurrentQuestion(): ?array
    {
        if (empty($this->questions)) {
            return null;
        }

        return $this->questions[$this->currentIndex];
    }

    public function selectAnswer(string $answerCode): void
    {
        $this->answers[$this->currentQuestion['id']] = strtoupper(trim($answerCode));
        $this->submitAnswer();
    }

    protected function submitAnswer(): void
    {
        if (!isset($this->answers[$this->currentQuestion['id']])) {
            return;
        }

        $selectedAnswer = $this->answers[$this->currentQuestion['id']];
        $correctAnswer = $this->currentQuestion['correct_answer_code'];

        $this->isCorrect = ($selectedAnswer === $correctAnswer);
        $this->showingFeedback = true;

        if ($this->isCorrect) {
            $this->feedbackMessage = 'Correct!';
            $this->correctCount++;
        } else {
            $this->feedbackMessage = 'Incorrect';
            
            // Record mistake if not already recorded
            $this->recordMistakeIfNecessary($selectedAnswer, $correctAnswer);
        }

        $this->saveSession();
    }

    protected function recordMistakeIfNecessary(string $wrongAnswer, string $correctAnswer): void
    {
        $existingMistake = Mistake::where('user_id', Auth::id())
            ->where('question_id', $this->currentQuestion['id'])
            ->first();

        if (!$existingMistake) {
            // Create new mistake record
            Mistake::create([
                'user_id' => Auth::id(),
                'question_id' => $this->currentQuestion['id'],
                'assessment_id' => null, // Not from assessment
                'selected_answer_code' => $wrongAnswer,
                'correct_answer_code' => $correctAnswer,
                'attempt_number' => 1,
                'first_mistaken_at' => now(),
                'last_mistaken_at' => now(),
            ]);
        } elseif (!$existingMistake->is_resolved) {
            // Increment attempt number
            $existingMistake->incrementAttempt();
        }
    }

    public function nextQuestion(): void
    {
        if ($this->currentIndex < count($this->questions) - 1) {
            $this->currentIndex++;
            $this->showingFeedback = false;
            $this->loadCurrentQuestion();
            $this->saveSession();
        } else {
            // Finished all questions
            $this->dispatch('practice-completed', [
                'correctCount' => $this->correctCount,
                'totalCount' => count($this->questions),
                'scorePercentage' => count($this->questions) > 0 
                    ? round(($this->correctCount / count($this->questions)) * 100, 1)
                    : 0,
            ])->to('parent');
        }
    }

    public function previousQuestion(): void
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
            $this->showingFeedback = false;
            $this->loadCurrentQuestion();
            $this->saveSession();
        }
    }

    protected function loadCurrentQuestion(): void
    {
        if (empty($this->questions)) {
            $this->currentQuestion = null;
            return;
        }

        $questionData = $this->questions[$this->currentIndex];
        $this->currentQuestion = \App\Models\Question::find($questionData['id']);
        $this->attemptNumber = isset($this->answers[$this->currentQuestion['id']]) ? 2 : 1;
    }

    protected function saveSession(): void
    {
        session(['practice_session' => [
            'questions' => $this->questions,
            'current_index' => $this->currentIndex,
            'answers' => $this->answers,
            'correct_count' => $this->correctCount,
            'start_time' => session('practice_session.start_time', now()),
            'practice_type' => 'interactive_practice',
        ]]);
    }

    public function getProgressPercentage(): float
    {
        $total = count($this->questions);
        $answered = count($this->answers);
        
        return $total > 0 ? round(($answered / $total) * 100) : 0;
    }

    public function getTotalQuestions(): int
    {
        return count($this->questions);
    }

    public function getCurrentQuestionNumber(): int
    {
        return $this->currentIndex + 1;
    }

    public function render()
    {
        return view('livewire.practice-mode');
    }
}
