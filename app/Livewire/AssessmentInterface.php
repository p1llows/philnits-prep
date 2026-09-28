<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\AssessmentAnswer;
use Livewire\Component;

class AssessmentInterface extends Component
{
    public Assessment $assessment;
    public array $questions = [];
    public array $answers = []; // [question_id => selected_answer_code]
    public int $currentQuestionIndex = 0;
    public bool $showingResults = false;
    
    protected $listeners = ['submitAssessment', 'answerChanged'];

    public function mount(Assessment $assessment)
    {
        $this->assessment = $assessment->load(['answers.question']);
        
        // Get all questions for this assessment
        $this->questions = $this->getQuestionsForAssessment();
        
        // Load user's previous answers
        foreach ($this->assessment->answers as $storedAnswer) {
            $this->answers[$storedAnswer->question_id] = $storedAnswer->selected_answer_code;
        }
    }

    protected function getQuestionsForAssessment(): array
    {
        // For initial assessment, get all published questions
        $questions = \App\Models\Question::published()
            ->with(['choices'])
            ->get()
            ->toArray();

        return $questions;
    }

    public function selectAnswer(string $questionId, string $answerCode): void
    {
        $this->answers[$questionId] = strtoupper(trim($answerCode));
        $this->dispatch('answer-selected')->to('parent');
    }

    public function getCurrentQuestion(): ?array
    {
        if (empty($this->questions)) {
            return null;
        }

        return $this->questions[$this->currentQuestionIndex] ?? null;
    }

    public function getQuestionNumber(): int
    {
        return $this->currentQuestionIndex + 1;
    }

    public function getTotalQuestions(): int
    {
        return count($this->questions);
    }

    public function navigateTo(int $index): void
    {
        if ($index >= 0 && $index < count($this->questions)) {
            $this->currentQuestionIndex = $index;
        }
    }

    public function nextQuestion(): void
    {
        if ($this->currentQuestionIndex < count($this->questions) - 1) {
            $this->currentQuestionIndex++;
        }
    }

    public function previousQuestion(): void
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }

    public function getProgressPercentage(): float
    {
        $answered = collect($this->answers)->count();
        $total = count($this->questions);
        
        return $total > 0 ? round(($answered / $total) * 100) : 0;
    }

    public function getUnansweredCount(): int
    {
        return count($this->questions) - count($this->answers);
    }

    public function canSubmit(): bool
    {
        // For initial assessment, allow submission even with unanswered questions
        // But warn the user
        return true;
    }

    public function submitAssessment()
    {
        // Validate that user has at least attempted some questions
        if (count($this->answers) === 0 && count($this->questions) > 0) {
            $this->dispatch('alert', [
                'message' => 'Please answer at least one question before submitting.',
                'type' => 'warning',
            ]);
            return;
        }

        $this->assessment->update([
            'time_spent_seconds' => $this->assessment->calculateTimeSpent(),
            'status' => 'submitted',
        ]);

        // Prepare answers for controller processing
        $answersPayload = [];
        foreach ($this->questions as $questionData) {
            $answer = $this->answers[$questionData['id']] ?? '';
            $answersPayload[] = [
                'question_id' => $questionData['id'],
                'selected_answer' => $answer,
            ];
        }

        // Dispatch event for backend processing
        $this->dispatch('process-assessment-submit', [
            'assessment_id' => $this->assessment->id,
            'answers' => $answersPayload,
        ]);

        $this->resetAnswers();
    }

    public function resetAnswers(): void
    {
        $this->answers = [];
        $this->currentQuestionIndex = 0;
        $this->showingResults = true;
    }

    public function render()
    {
        return view('livewire.assessment-interface');
    }
}
