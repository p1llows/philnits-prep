<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'source_package_id',
        'source_question_number',
        'source_reference',
        'question_text',
        'explanation',
        'explanation_type',
        'explanation_approved',
        'correct_answer_code',
        'status',
        'topic_id',
        'suggested_topic_id',
        'difficulty',
        'created_by',
        'validated_by',
        'published_by',
        'explanation_created_by',
        'imported_at',
        'validated_at',
        'published_at',
        'explanation_created_at',
    ];

    /**
     * Get the source package this question belongs to.
     */
    public function sourcePackage(): BelongsTo
    {
        return $this->belongsTo(SourcePackage::class);
    }

    /**
     * Get the topic for this question.
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * Get the suggested AI classification topic.
     */
    public function suggestedTopic(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'suggested_topic_id');
    }

    /**
     * Get all choices for this question.
     */
    public function choices(): HasMany
    {
        return $this->hasMany(Choice::class)->orderBy('display_order');
    }

    /**
     * Get the user who created this question.
     */
    public function createdBy(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who validated this question.
     */
    public function validatedBy(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Get the user who published this question.
     */
    public function publishedBy(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    /**
     * Get the user who created the explanation.
     */
    public function explanationCreatedBy(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'explanation_created_by');
    }

    /**
     * Get the correct choice object.
     */
    public function getCorrectChoiceAttribute(): ?Choice
    {
        return $this->choices()->where('code', $this->correct_answer_code)->first();
    }

    /**
     * Scope a query to only include questions with specific status.
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include published questions.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('topic_id');
    }

    /**
     * Scope a query to only include unpublished questions (pending review).
     */
    public function scopeUnpublished($query)
    {
        return $query->where('status', '!=', 'published');
    }

    /**
     * Scope a query to exclude archived/deleted questions.
     */
    public function scopeActive($query)
    {
        return $query->withoutTrashed()
            ->whereIn('status', ['approved', 'published']);
    }

    /**
     * Check if the question needs review.
     */
    public function needsReview(): bool
    {
        return in_array($this->status, ['draft', 'pending_review', 'validated']) && !$this->explanation_approved;
    }

    /**
     * Check if the question is available for practice.
     */
    public function isAvailableForPractice(): bool
    {
        return $this->status === 'published' 
            && $this->topic_id !== null 
            && $this->deletedAt === null;
    }

    /**
     * Set the status and update audit timestamps.
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;

        if ($status === 'imported') {
            $this->imported_at = now();
        }

        if (in_array($status, ['validated', 'approved'])) {
            $this->validated_at = now();
        }

        if ($status === 'published') {
            $this->published_at = now();
        }
    }

    /**
     * Mark explanation as AI-generated or admin-created.
     */
    public function markExplanationAs(string $type, ?int $userId = null): void
    {
        $this->explanation_type = $type;
        $this->explanation_approved = false; // Reset approval
        
        if ($type === 'admin_created' && $userId) {
            $this->explanation_created_by = $userId;
        } elseif ($type === 'ai_generated') {
            // Don't set explanation_created_by for AI
        }
        
        $this->explanation_created_at = now();
    }
}
