<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SourcePackage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'questions_file_path',
        'answers_file_path',
        'questions_file_hash',
        'answers_file_hash',
        'status',
        'question_count',
        'answer_count',
        'validated_count',
        'published_count',
        'source_name',
        'source_date',
        'attribution_note',
        'created_by',
        'imported_by',
        'validated_by',
        'published_by',
        'imported_at',
        'validated_at',
        'published_at',
    ];

    /**
     * Get the source questions associated with this package.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'source_package_id');
    }

    /**
     * Get the user who created this package.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who imported this package.
     */
    public function importedBy(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'imported_by');
    }

    /**
     * Get the user who validated this package.
     */
    public function validatedBy(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Get the user who published this package.
     */
    public function publishedBy(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    /**
     * Scope a query to only include packages with specific status.
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Check if the package is ready for import.
     */
    public function isReadyForImport(): bool
    {
        return in_array($this->status, ['draft', 'validation_failed']);
    }

    /**
     * Check if the package has been published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Check if the package needs review.
     */
    public function needsReview(): bool
    {
        return $this->status === 'pending_review';
    }

    /**
     * Get the percentage of questions validated.
     */
    public function validationProgress(): float
    {
        if ($this->question_count === 0) {
            return 0.0;
        }

        return ($this->validated_count / $this->question_count) * 100;
    }

    /**
     * Get the percentage of questions published.
     */
    public function publicationProgress(): float
    {
        if ($this->question_count === 0) {
            return 0.0;
        }

        return ($this->published_count / $this->question_count) * 100;
    }

    /**
     * Set the status and update audit timestamps.
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;

        if ($status === 'importing') {
            $this->imported_at = now();
        }

        if ($status === 'validating' || $status === 'approved') {
            $this->validated_at = now();
        }

        if ($status === 'published') {
            $this->published_at = now();
        }
    }
}
