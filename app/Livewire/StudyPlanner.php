<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StudySchedule;
use App\Models\StudySession;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;

class StudyPlanner extends Component
{
    public array $mySchedules = [];
    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public string $scheduleTitle = '';
    public string $scheduleDescription = '';
    public string $startDate = '';
    public ?string $endDate = null;
    public int $weeklyStudyHours = 5;
    public string $intensity = 'moderate';
    public array $selectedDays = [];
    public ?int $editingScheduleId = null;

    protected $rules = [
        'scheduleTitle' => 'required|string|max:255',
        'scheduleDescription' => 'nullable|string',
        'startDate' => 'required|date',
        'endDate' => 'nullable|date|after_or_equal:startDate',
        'weeklyStudyHours' => 'required|integer|min:1|max:40',
        'intensity' => 'required|in:light,moderate,intense',
        'selectedDays' => 'array',
    ];

    public function mount()
    {
        $this->loadSchedules();
        
        // Default selected days (moderate study - weekdays)
        $this->selectedDays = ['mon', 'tue', 'wed', 'thu', 'fri'];
    }

    public function loadSchedules(): void
    {
        $this->mySchedules = StudySchedule::where('user_id', Auth::id())
            ->orderBy('is_committed', 'desc')
            ->orderBy('start_date', 'desc')
            ->get()
            ->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'title' => $schedule->title,
                    'description' => $schedule->description,
                    'start_date' => $schedule->start_date,
                    'end_date' => $schedule->end_date,
                    'weekly_hours' => $schedule->weekly_study_hours,
                    'intensity' => $schedule->intensity,
                    'is_active' => $schedule->is_active,
                    'is_committed' => $schedule->is_committed,
                    'committed_at' => $schedule->committed_at?->diffForHumans(),
                    'total_sessions' => $schedule->sessions()->count(),
                    'completed_sessions' => $schedule->sessions()->where('status', 'completed')->count(),
                    'upcoming_sessions' => $schedule->upcomingSessions()->take(5)->get(),
                ];
            })
            ->toArray();
    }

    public function openCreateModal(): void
    {
        $this->resetFields();
        $this->showCreateModal = true;
    }

    public function openEditModal(int $scheduleId): void
    {
        $schedule = StudySchedule::findOrFail($scheduleId);
        
        $this->editingScheduleId = $scheduleId;
        $this->scheduleTitle = $schedule->title;
        $this->scheduleDescription = $schedule->description ?? '';
        $this->startDate = $schedule->start_date->format('Y-m-d');
        $this->endDate = $schedule->end_date?->format('Y-m-d') ?: '';
        $this->weeklyStudyHours = $schedule->weekly_study_hours;
        $this->intensity = $schedule->intensity;
        $this->selectedDays = is_array($schedule->schedule_slots) 
            ? array_column($schedule->schedule_slots, 'day') 
            : [];
        $this->showEditModal = true;
    }

    protected function resetFields(): void
    {
        $this->scheduleTitle = '';
        $this->scheduleDescription = '';
        $this->startDate = now()->format('Y-m-d');
        $this->endDate = '';
        $this->weeklyStudyHours = 5;
        $this->intensity = 'moderate';
        $this->selectedDays = ['mon', 'tue', 'wed', 'thu', 'fri'];
        $this->editingScheduleId = null;
    }

    public function saveSchedule(): void
    {
        $this->validate();

        // Build schedule slots from selected days
        $scheduleSlots = collect($this->selectedDays)->map(function ($day) {
            return [
                'day' => $day,
                'time' => '19:00-20:30', // Default time slot
            ];
        })->toArray();

        if ($this->editingScheduleId) {
            // Update existing schedule
            $schedule = StudySchedule::findOrFail($this->editingScheduleId);
            $schedule->update([
                'title' => $this->scheduleTitle,
                'description' => $this->scheduleDescription,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate ?: null,
                'weekly_study_hours' => $this->weeklyStudyHours,
                'intensity' => $this->intensity,
                'schedule_slots' => $scheduleSlots,
            ]);

            $message = 'Schedule updated successfully!';
        } else {
            // Create new schedule
            $schedule = StudySchedule::create([
                'user_id' => Auth::id(),
                'title' => $this->scheduleTitle,
                'description' => $this->scheduleDescription,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate ?: null,
                'weekly_study_hours' => $this->weeklyStudyHours,
                'intensity' => $this->intensity,
                'schedule_slots' => $scheduleSlots,
                'is_active' => true,
            ]);

            $message = 'Study schedule created successfully!';
        }

        $this->closeModals();
        $this->loadSchedules();
        $this->dispatch('schedule-saved');
    }

    public function toggleActive(int $scheduleId): void
    {
        $schedule = StudySchedule::findOrFail($scheduleId);
        $schedule->update(['is_active' => !$schedule->is_active]);
        $this->loadSchedules();
    }

    public function commitToSchedule(int $scheduleId): void
    {
        $schedule = StudySchedule::findOrFail($scheduleId);
        $schedule->markAsCommitted();
        $this->loadSchedules();
        session()->flash('success', 'You\'ve committed to this study schedule!');
    }

    public function deleteSchedule(int $scheduleId): void
    {
        $schedule = StudySchedule::findOrFail($scheduleId);
        
        if ($schedule->sessions()->where('status', 'completed')->count() > 0) {
            session()->flash('error', 'Cannot delete schedule with completed sessions.');
            return;
        }

        $schedule->delete();
        $this->loadSchedules();
        session()->flash('success', 'Schedule deleted.');
    }

    public function closeModals(): void
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->resetFields();
    }

    public function render()
    {
        return view('livewire.study-planner');
    }
}
