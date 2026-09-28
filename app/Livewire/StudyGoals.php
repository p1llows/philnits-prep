<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StudyGoal;
use App\Models\Topic;
use App\Models\Mistake;
use Illuminate\Support\Facades\Auth;

class StudyGoals extends Component
{
    public array $myGoals = [];
    public bool $showCreateModal = false;
    public string $goalTitle = '';
    public string $goalDescription = '';
    public string $targetDate = '';
    public string $goalType = 'score_target';
    public int $targetValue = 0;
    public string $weakTopicId = '';

    protected $rules = [
        'goalTitle' => 'required|string|max:255',
        'goalDescription' => 'nullable|string',
        'targetDate' => 'required|date|after_or_equal:today',
        'goalType' => 'required|in:score_target,assessment_count,mistake_resolution,topic_mastery,hours_studied',
        'targetValue' => 'required|integer|min:1',
    ];

    public function mount()
    {
        $this->loadGoals();
    }

    public function loadGoals(): void
    {
        $this->myGoals = StudyGoal::where('user_id', Auth::id())
            ->orderBy('is_achieved', 'desc')
            ->orderBy('target_date', 'asc')
            ->get()
            ->map(function ($goal) {
                return [
                    'id' => $goal->id,
                    'title' => $goal->title,
                    'description' => $goal->description,
                    'type' => $goal->type,
                    'type_display' => $this->getTypeDisplayName($goal->type),
                    'target_value' => $goal->target_value,
                    'current_value' => $goal->current_value,
                    'progress_percentage' => $goal->progress_percentage,
                    'target_date' => $goal->target_date,
                    'is_achieved' => $goal->is_achieved,
                    'is_overdue' => $goal->is_overdue,
                    'is_approaching_deadline' => $goal->is_approaching_deadline(7),
                ];
            })
            ->toArray();
    }

    public function openCreateModal(string $type = null): void
    {
        $this->resetFields();
        if ($type) {
            $this->goalType = $type;
        }
        $this->showCreateModal = true;
    }

    protected function resetFields(): void
    {
        $this->goalTitle = '';
        $this->goalDescription = '';
        $this->targetDate = now()->addMonths(2)->format('Y-m-d'); // Default 2 months
        $this->goalType = 'score_target';
        $this->targetValue = 60; // Default 60% benchmark
        $this->weakTopicId = '';
    }

    public function saveGoal(): void
    {
        $this->validate();

        // Create study goal record
        StudyGoal::create([
            'user_id' => Auth::id(),
            'title' => $this->goalTitle,
            'description' => $this->goalDescription,
            'target_date' => $this->targetDate,
            'type' => $this->goalType,
            'target_value' => $this->targetValue,
            'current_value' => 0,
        ]);

        $this->closeModals();
        $this->loadGoals();
        
        session()->flash('success', 'Study goal created successfully!');
    }

    public function resetGoal(int $goalId): void
    {
        $goal = StudyGoal::findOrFail($goalId);
        $newTarget = request('new_target', $goal->target_value);
        $goal->resetGoal((int)$newTarget);
        $this->loadGoals();
        session()->flash('success', 'Goal reset with new target value.');
    }

    public function deleteGoal(int $goalId): void
    {
        StudyGoal::destroy($goalId);
        $this->loadGoals();
        session()->flash('success', 'Goal deleted.');
    }

    protected function closeModals(): void
    {
        $this->showCreateModal = false;
        $this->resetFields();
    }

    protected function getTypeDisplayName(string $type): string
    {
        return match($type) {
            'score_target' => 'Achieve Target Score',
            'assessment_count' => 'Complete Assessments',
            'mistake_resolution' => 'Resolve Mistakes',
            'topic_mastery' => 'Master Topics',
            'hours_studied' => 'Study Hours',
            default => ucfirst(str_replace('_', ' ', $type)),
        };
    }

    public function render()
    {
        return view('livewire.study-goals');
    }
}
