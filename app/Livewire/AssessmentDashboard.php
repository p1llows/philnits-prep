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
    public $syllabusFields = [];

    public function mount()
    {
        $user = Auth::user();
        
        // Load official ITPEC syllabus fields and topics
        $this->loadSyllabusFields();

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

    protected function loadSyllabusFields(): void
    {
        $fields = [
            'strategy' => [
                'name' => 'Strategy Field',
                'field_code' => 'Field 01',
                'official_weight' => '~35% (35 Questions)',
                'min_pass' => '30%',
                'color' => '#854D0E',
                'badge_color' => 'bg-amber-100 text-amber-800 border-amber-200',
                'bar_color' => 'bg-amber-500',
            ],
            'management' => [
                'name' => 'Management Field',
                'field_code' => 'Field 02',
                'official_weight' => '~25% (25 Questions)',
                'min_pass' => '30%',
                'color' => '#047857',
                'badge_color' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                'bar_color' => 'bg-emerald-500',
            ],
            'technology' => [
                'name' => 'Technology Field',
                'field_code' => 'Field 03',
                'official_weight' => '~40% (40 Questions incl. Pseudocode)',
                'min_pass' => '30%',
                'color' => '#1E3A8A',
                'badge_color' => 'bg-blue-100 text-blue-800 border-blue-200',
                'bar_color' => 'bg-blue-600',
            ],
        ];

        foreach ($fields as $key => $meta) {
            $topics = \App\Models\Topic::where('field', $key)
                ->withCount('questions')
                ->orderBy('sort_order')
                ->get();

            $fields[$key]['topics'] = $topics;
            $fields[$key]['topics_count'] = $topics->count();
            $fields[$key]['total_questions'] = $topics->sum('questions_count');
        }

        $this->syllabusFields = $fields;
    }

    public function render()
    {
        return view('livewire.assessment-dashboard');
    }
}
