<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mistake extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'question_id',
        'assessment_id',
        'selected_answer_code',
        'correct_answer_code',
        'attempt_number',
        'first_mistaken_at',
        'last_mistaken_at',
        'is_resolved',
        'resolved_at',
        'attempts_to_resolve',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_resolved' => 'boolean',
        'first_mistaken_at' => 'datetime',
        'last_mistaken_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the user who made this mistake.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the question associated with this mistake.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the assessment where this mistake occurred (optional).
     */
    public function assessment(): ?BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    /**
     * Scope a query to only include unresolved mistakes.
     */
    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    /**
     * Scope a query to only include resolved mistakes.
     */
    public function scopeResolved($query)
    {
        return $query->where('is_resolved', true);
    }

    /**
     * Resolve this mistake.
     */
    public function resolve(int $attempts = 1): void
    {
        $this->is_resolved = true;
        $this->resolved_at = now();
        $this->attempts_to_resolve += $attempts;
        $this->save();
    }

    /**
     * Increment the attempt number.
     */
    public function incrementAttempt(): void
    {
        $this->attempt_number++;
        $this->last_mistaken_at = now();
        
        if ($this->attempt_number > 1 && !$this->is_resolved) {
            $this->increment('attempts_to_resolve');
        }
        
        $this->save();
    }
}
