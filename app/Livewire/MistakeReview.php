<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Mistake;
use Illuminate\Support\Facades\Auth;

class MistakeReview extends Component
{
    public array $mistakes = [];
    public int $currentIndex = 0;
    public bool $showingFeedback = false;
    public bool $isCorrect = false;
    public array $answers = [];

    protected $listeners = ['nextMistake', 'previousMistake'];

    public function mount()
    {
        $this->loadMistakes();
    }

    protected function loadMistakes(): void
    {
        $this->mistakes = Mistake::where('user_id', Auth::id())
            ->with(['question.choices', 'question.topic'])
            ->orderBy('first_mistaken_at') // Show oldest mistakes first (spaced repetition)
            ->get()
            ->toArray();

        if (empty($this->mistakes)) {
            // No mistakes, redirect to dashboard
            return;
        }
    }

    public function getCurrentMistake(): ?array
    {
        if (empty($this->mistakes)) {
            return null;
        }

        return $this->mistakes[$this->currentIndex];
    }

    public function selectAnswer(string $answerCode): void
    {
        $selectedAnswer = strtoupper(trim($answerCode));
        $correctAnswer = $this->mistakes[$this->currentIndex]['correct_answer_code'];

        $this->isCorrect = ($selectedAnswer === $correctAnswer);
        $this->showingFeedback = true;
        $this->answers[$this->currentIndex] = $selectedAnswer;

        if ($this->isCorrect) {
            // Mark as resolved
            $this->resolveMistake();
        } else {
            // Increment attempt number
            $this->incrementAttempt();
        }
    }

    protected function resolveMistake(): void
    {
        $mistakeId = $this->mistakes[$this->currentIndex]['id'];
        
        Mistake::where('id', $mistakeId)->update([
            'is_resolved' => true,
            'resolved_at' => now(),
        ]);

        // Remove this mistake from our list
        array_splice($this->mistakes, $this->currentIndex, 1);
        
        // Adjust index if needed
        if ($this->currentIndex >= count($this->mistakes)) {
            $this->currentIndex = max(0, count($this->mistakes) - 1);
        }

        // Re-save session if needed
        $this->saveProgress();
    }

    protected function incrementAttempt(): void
    {
        $mistakeId = $this->mistakes[$this->currentIndex]['id'];
        
        Mistake::where('id', $mistakeId)->increment('attempt_number');
        
        Mistake::where('id', $mistakeId)->update([
            'last_mistaken_at' => now(),
        ]);
    }

    public function nextMistake(): void
    {
        if ($this->currentIndex < count($this->mistakes) - 1) {
            $this->currentIndex++;
            $this->showingFeedback = false;
            $this->saveProgress();
        } else {
            $this->dispatch('mistakes-review-completed');
        }
    }

    public function previousMistake(): void
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
            $this->showingFeedback = false;
            $this->saveProgress();
        }
    }

    protected function saveProgress(): void
    {
        session(['mistake_review_session' => [
            'current_index' => $this->currentIndex,
            'answers' => $this->answers,
        ]]);
    }

    public function getUnresolvedCount(): int
    {
        return collect($this->mistakes)->count();
    }

    public function getTotalMistakes(): int
    {
        return Mistake::where('user_id', Auth::id())
            ->where('is_resolved', false)
            ->count();
    }

    public function getResolvedCount(): int
    {
        return Mistake::where('user_id', Auth::id())
            ->where('is_resolved', true)
            ->count();
    }

    public function render()
    {
        return view('livewire.mistake-review');
    }
}
