<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudySchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'weekly_study_hours',
        'intensity',
        'schedule_slots',
        'is_active',
        'is_committed',
        'committed_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'schedule_slots' => 'array',
        'is_active' => 'boolean',
        'is_committed' => 'boolean',
        'committed_at' => 'datetime',
    ];

    /**
     * Get the sessions for this schedule.
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(StudySession::class);
    }

    /**
     * Get upcoming sessions for this schedule.
     */
    public function upcomingSessions(): HasMany
    {
        return $this->sessions()
            ->where('scheduled_date', '>=', now()->toDateString())
            ->orderBy('scheduled_date')
            ->orderBy('scheduled_time');
    }

    /**
     * Get completed sessions for this schedule.
     */
    public function completedSessions(): HasMany
    {
        return $this->sessions()
            ->where('status', 'completed')
            ->orderBy('scheduled_date', 'desc');
    }

    /**
     * Calculate total planned hours for a date range.
     */
    public function getTotalHoursAttribute(): float
    {
        if ($this->end_date) {
            $totalDays = now()->diffInDays($this->start_date) + 1;
            return ($this->weekly_study_hours / 7) * min($totalDays, 7);
        }
        
        // Rolling calculation - last 7 days
        $weekAgo = now()->subDays(7);
        $sessions = $this->sessions()
            ->where('scheduled_date', '>=', $weekAgo->toDateString())
            ->where('status', 'completed')
            ->sum('actual_duration_minutes');
            
        return $sessions / 60;
    }

    /**
     * Get intensity multiplier for study load calculation.
     */
    public function getIntensityMultiplierAttribute(): float
    {
        return match($this->intensity) {
            'light' => 0.75,
            'moderate' => 1.0,
            'intense' => 1.5,
            default => 1.0,
        };
    }

    /**
     * Check if schedule is currently active (within date range).
     */
    public function isActivePeriod(): bool
    {
        return now()->between($this->start_date, $this->end_date ?? now());
    }

    /**
     * Mark schedule as committed.
     */
    public function markAsCommitted(): void
    {
        $this->update([
            'is_committed' => true,
            'committed_at' => now(),
        ]);
    }
}
