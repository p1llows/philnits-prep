<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Assessment;
use App\Models\Mistake;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProgressAnalytics extends Component
{
    public array $assessmentHistory = [];
    public float $overallScore = 0;
    public int $totalAssessments = 0;
    public int $bestScore = 0;
    public int $worstScore = 0;
    public array $topicPerformance = [];
    public array $mistakeStats = [];
    public int $questionsAnswered = 0;
    public int $mistakesResolved = 0;
    public string $currentPeriod = '30days'; // daily, weekly, monthly, all

    public function mount()
    {
        $this->loadAnalytics();
    }

    protected function loadAnalytics(): void
    {
        $userId = Auth::id();

        // Assessment history for the selected period
        $startDate = $this->getStartDate($this->currentPeriod);
        
        $this->assessmentHistory = Assessment::where('user_id', $userId)
            ->where('completed_at', '>=', $startDate)
            ->with(['topicPerformance'])
            ->orderBy('started_at', 'desc')
            ->take(20)
            ->get()
            ->toArray();

        // Calculate aggregate stats
        $assessments = Assessment::where('user_id', $userId)->get();
        
        $this->totalAssessments = $assessments->count();
        
        if ($this->totalAssessments > 0) {
            $scores = $assessments->pluck('score');
            $this->overallScore = round($scores->avg(), 1);
            $this->bestScore = $scores->max();
            $this->worstScore = $scores->min();
        } else {
            $this->overallScore = 0;
            $this->bestScore = 0;
            $this->worstScore = 0;
        }

        // Mistake statistics
        $this->mistakeStats = [
            'total_mistakes' => Mistake::where('user_id', $userId)->count(),
            'resolved_mistakes' => Mistake::where('user_id', $userId)->where('is_resolved', true)->count(),
            'unresolved_mistakes' => Mistake::where('user_id', $userId)->where('is_resolved', false)->count(),
        ];

        // Recent practice activity (last 7 days)
        $recentActivity = $assessments->where('completed_at', '>=', Carbon::now()->subDays(7));
        $this->questionsAnswered = $recentActivity->sum(function ($a) {
            return count($a->answers);
        });

        // Topic performance breakdown
        $this->calculateTopicPerformance($userId);
    }

    protected function getStartDate(string $period): \DateTimeInterface
    {
        $now = now();
        
        return match($period) {
            'daily' => $now->startOfDay(),
            'weekly' => $now->startOfWeek(),
            'monthly' => $now->startOfMonth(),
            'all' => Carbon::createFromDate(2024, 1, 1),
            default => $now->subDays(30), // default 30 days
        };
    }

    protected function calculateTopicPerformance(int $userId): void
    {
        // Get assessments with topic performance data
        $assessments = Assessment::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->with(['topicPerformance'])
            ->get();

        if ($assessments->isEmpty()) {
            $this->topicPerformance = [];
            return;
        }

        // Aggregate by topic
        $topicData = collect();
        
        foreach ($assessments as $assessment) {
            foreach ($assessment->topic_performance ?? [] as $topicId => $performance) {
                $topicData->push([
                    'topic_id' => $topicId,
                    'topic_name' => $performance['topic_name'],
                    'question_count' => $performance['question_count'],
                    'correct_count' => $performance['correct_count'],
                    'score' => $performance['score'],
                ]);
            }
        }

        // Group by topic and average scores
        $this->topicPerformance = $topicData->groupBy('topic_id')->map(function ($topicItems, $topicId) {
            $totalCount = $topicItems->sum('question_count');
            $correctCount = $topicItems->sum('correct_count');
            
            return [
                'topic_id' => $topicId,
                'topic_name' => $topicItems->first('topic_name'),
                'total_questions' => $totalCount,
                'correct_answers' => $correctCount,
                'average_score' => $totalCount > 0 ? round(($correctCount / $totalCount) * 100, 1) : 0,
                'attempts' => $topicItems->count(),
            ];
        })->sortByDesc('average_score')->values()->toArray();
    }

    public function setPeriod(string $period): void
    {
        $this->currentPeriod = $period;
        $this->loadAnalytics();
    }

    public function getTrendGraphData(): array
    {
        usort($this->assessmentHistory, function($a, $b) {
            return strtotime($a['started_at']) - strtotime($b['started_at']);
        });

        return [
            'labels' => array_map(fn($a) => date('M j', strtotime($a['started_at'])), $this->assessmentHistory),
            'scores' => array_column($this->assessmentHistory, 'score'),
        ];
    }

    public function render()
    {
        return view('livewire.progress-analytics');
    }
}
