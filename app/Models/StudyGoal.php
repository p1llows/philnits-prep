<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudyGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'target_date',
        'type',
        'target_value',
        'current_value',
        'is_achieved',
        'achieved_at',
    ];

    protected $casts = [
        'target_date' => 'date',
        'is_achieved' => 'boolean',
        'achieved_at' => 'datetime',
    ];

    /**
     * Get progress percentage toward this goal.
     */
    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_value <= 0) {
            return 100;
        }
        
        return min(100, round(($this->current_value / $this->target_value) * 100, 1));
    }

    /**
     * Check if goal is overdue.
     */
    public function isOverdue(): bool
    {
        return !$this->is_achieved && now()->isAfter($this->target_date);
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->isOverdue();
    }

    /**
     * Check if goal is approaching deadline.
     */
    public function isApproachingDeadline(int $days = 7): bool
    {
        return !$this->is_achieved && 
               now()->between($this->target_date->copy()->subDays($days), $this->target_date);
    }

    public function is_approaching_deadline(int $days = 7): bool
    {
        return $this->isApproachingDeadline($days);
    }

    /**
     * Update current value and check if achieved.
     */
    public function updateCurrentValue(int $increment): void
    {
        $newValue = min($this->target_value, $this->current_value + $increment);
        
        $this->update([
            'current_value' => $newValue,
            'is_achieved' => $newValue >= $this->target_value,
            'achieved_at' => $newValue >= $this->target_value ? now() : null,
        ]);
    }

    /**
     * Reset goal after completion.
     */
    public function resetGoal(int $newTargetValue): void
    {
        $this->update([
            'current_value' => 0,
            'is_achieved' => false,
            'achieved_at' => null,
            'target_value' => $newTargetValue,
        ]);
    }
}
