<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Assessment;
use App\Models\Mistake;
use Illuminate\Support\Facades\Auth;

class AssessmentDashboard extends Component
{
    public $currentScore = null;
    public $benchmark = 60;
    public $weakTopics = [];
    public $recentAssessments = [];
    public $activeMistakes = [];
    public $hasCompletedInitialAssessment = false;
    public $totalTopics = 0;
    public $completedTopics = 0;
    public $practiceQuestionsCount = 0;
    public $averageScore = null;
    public $progressPercentage = 0;

    public function mount()
    {
        $user = Auth::user();
        
        // Get most recent assessment with a score
        $latestAssessment = Assessment::where('user_id', $user->id)
            ->whereNotNull('score_percentage')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($latestAssessment) {
            $this->currentScore = $latestAssessment->score_percentage;
            $this->hasCompletedInitialAssessment = true;
        }

        // Compute average score across assessments
        $avg = Assessment::where('user_id', $user->id)
            ->whereNotNull('score_percentage')
            ->avg('score_percentage');
        $this->averageScore = $avg !== null ? round($avg) : null;

        // Topics stats
        $this->totalTopics = \App\Models\Topic::where('is_active', true)->count();
        if ($this->totalTopics === 0) {
            $this->totalTopics = \App\Models\Topic::count();
        }

        // Count questions answered by this user
        $this->practiceQuestionsCount = \App\Models\AssessmentAnswer::whereHas('assessment', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        // Topics with answers
        $practicedTopicIds = \App\Models\Topic::whereHas('questions.assessmentAnswers', function ($q) use ($user) {
            $q->whereHas('assessment', function ($aq) use ($user) {
                $aq->where('user_id', $user->id);
            });
        })->count();
        $this->completedTopics = $practicedTopicIds;

        // Progress percentage
        if ($this->totalTopics > 0) {
            $this->progressPercentage = min(100, (int) round(($this->completedTopics / $this->totalTopics) * 100));
        } else {
            $this->progressPercentage = 0;
        }

        // Get weak topics (topics where user has low performance)
        $this->getWeakTopics($user);
        
        // Get recent assessments
        $this->getRecentAssessments($user);
        
        // Get active mistakes
        $this->getActiveMistakes($user);
    }

    protected function getWeakTopics($user)
    {
        // This would typically query assessment answers by topic
        // For now, we'll use a placeholder
        
        $this->weakTopics = [
            // Example: Topic::where('id', 1)->first(),
            // etc.
        ];
    }

    protected function getRecentAssessments($user)
    {
        $this->recentAssessments = Assessment::where('user_id', $user->id)
            ->whereNotNull('score_percentage')
            ->orderBy('submitted_at', 'desc')
            ->take(5)
            ->get();
    }

    protected function getActiveMistakes($user)
    {
        $this->activeMistakes = Mistake::where('user_id', $user->id)
            ->where('is_resolved', false)
            ->with(['question.choices'])
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.assessment-dashboard');
    }
}
