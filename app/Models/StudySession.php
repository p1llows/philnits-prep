<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudySession extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'topic_id',
        'question_id',
        'session_type',
        'scheduled_date',
        'scheduled_time',
        'planned_duration_minutes',
        'status',
        'actual_duration_minutes',
        'score_percentage',
        'notes',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'scheduled_time' => 'datetime:H:i',
        'planned_duration_minutes' => 'integer',
        'actual_duration_minutes' => 'integer',
        'score_percentage' => 'float:2',
    ];

    /**
     * Get the schedule this session belongs to.
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(StudySchedule::class, 'schedule_id');
    }

    /**
     * Get the topic for this session if applicable.
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * Get the question for this session if applicable.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Check if session is upcoming or today.
     */
    public function isUpcoming(): bool
    {
        return $this->scheduled_date >= now()->toDateString() 
            && $this->status === 'planned';
    }

    /**
     * Check if session should start now (within 15 minutes).
     */
    public function shouldStartSoon(): bool
    {
        if (!$this->scheduled_time || !$this->isUpcoming()) {
            return false;
        }

        $now = now();
        $scheduled = $now->copy()->setTimeFromDateTime($this->scheduled_time);
        
        return abs($now->diffInSeconds($scheduled)) <= 900; // 15 minutes
    }

    /**
     * Mark session as completed.
     */
    public function markAsCompleted(?float $score = null, ?int $duration = null, ?string $notes = null): void
    {
        $this->update([
            'status' => 'completed',
            'actual_duration_minutes' => $duration ?? $this->planned_duration_minutes,
            'score_percentage' => $score,
            'notes' => $notes,
        ]);
    }

    /**
     * Mark session as skipped.
     */
    public function markAsSkipped(): void
    {
        $this->update([
            'status' => 'skipped',
            'notes' => $this->notes . " | Skipped on " . now()->format('Y-m-d H:i'),
        ]);
    }

    /**
     * Get progress percentage for a goal (if related).
     */
    public function getProgressForGoal(): ?array
    {
        if ($this->schedule && $this->schedule->user_id) {
            $userGoals = \App\Models\StudyGoal::where('user_id', $this->schedule->user_id)
                ->where('type', 'hours_studied')
                ->where('is_achieved', false)
                ->get();

            if ($userGoals->count() > 0) {
                $totalTarget = $userGoals->sum('target_value');
                $totalCurrent = $userGoals->sum('current_value');
                
                return [
                    'target' => $totalTarget,
                    'current' => $totalCurrent,
                    'percentage' => $totalTarget > 0 ? round(($totalCurrent / $totalTarget) * 100) : 0,
                ];
            }
        }

        return null;
    }
}
