<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function ($assessment) {
            $assessment->started_at ??= now();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'assessment_type',
        'source_package_name',
        'total_questions',
        'question_selection',
        'selected_topic_id',
        'started_at',
        'submitted_at',
        'completed_at',
        'time_spent_seconds',
        'correct_count',
        'incorrect_count',
        'score',
        'score_percentage',
        'topic_performance',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'completed_at' => 'datetime',
        'topic_performance' => 'array',
        'score_percentage' => 'float',
        'score' => 'float',
    ];

    /**
     * Get the user who took this assessment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all answers for this assessment.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(AssessmentAnswer::class);
    }

    /**
     * Get the topic-level breakdown of results.
     */
    public function getTopicPerformanceAttribute(): array
    {
        $performance = [];
        
        $answers = $this->answers()
            ->with(['question.topic'])
            ->get();
        
        foreach ($answers as $answer) {
            if ($answer->question && $answer->question->topic) {
                $topic = $answer->question->topic;
                
                if (!isset($performance[$topic->id])) {
                    $performance[$topic->id] = [
                        'topic_id' => $topic->id,
                        'topic_name' => $topic->name,
                        'total' => 0,
                        'correct' => 0,
                        'incorrect' => 0,
                    ];
                }
                
                $performance[$topic->id]['total']++;
                
                if ($answer->is_correct) {
                    $performance[$topic->id]['correct']++;
                } else {
                    $performance[$topic->id]['incorrect']++;
                }
            }
        }
        
        return array_values($performance);
    }

    /**
     * Scope a query to only include assessments for a specific user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to order by date (newest first).
     */
    public function scopeOldestFirst($query)
    {
        return $query->orderBy('started_at');
    }

    /**
     * Check if the assessment is still in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if the assessment has been submitted.
     */
    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    /**
     * Calculate time spent in seconds.
     */
    public function calculateTimeSpent(): int
    {
        if (!$this->submitted_at || !$this->started_at) {
            return 0;
        }
        
        return now()->diffInSeconds($this->started_at);
    }

    /**
     * Get time spent in seconds.
     */
    public function timeSpentSeconds(): int
    {
        if ($this->time_spent_seconds) {
            return (int) $this->time_spent_seconds;
        }

        if ($this->started_at && ($this->completed_at || $this->submitted_at)) {
            $end = $this->completed_at ?? $this->submitted_at;
            return (int) $this->started_at->diffInSeconds($end);
        }

        return 0;
    }

    /**
     * Check if assessment score meets or exceeds pass threshold (60%).
     */
    public function passThreshold(): bool
    {
        return ($this->score ?? 0) >= 60.0;
    }

    public function getScoreAttribute(): float
    {
        return (float) ($this->attributes['score_percentage'] ?? $this->attributes['score'] ?? 0);
    }

    public function setScoreAttribute($value): void
    {
        $this->attributes['score_percentage'] = $value;
        $this->attributes['score'] = $value;
    }

    public function getCompletedAtAttribute()
    {
        return $this->submitted_at;
    }

    public function setCompletedAtAttribute($value): void
    {
        $this->attributes['submitted_at'] = $value;
    }

    /**
     * Update time spent.
     */
    public function updateTimeSpent(): void
    {
        $this->time_spent_seconds = $this->calculateTimeSpent();
        $this->save();
    }
}
