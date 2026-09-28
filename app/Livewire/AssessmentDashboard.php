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
