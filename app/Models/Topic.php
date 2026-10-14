<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'field', // 'technology', 'management', 'strategy'
        'code',
        'description',
        'color',
        'sort_order',
        'is_active',
    ];

    /**
     * Get official human-readable field label.
     */
    public function getFieldLabelAttribute(): string
    {
        return match($this->field) {
            'strategy' => 'Strategy (S)',
            'management' => 'Management (M)',
            'technology' => 'Technology (T)',
            default => 'Technology (T)',
        };
    }

    /**
     * Scope for a specific ITPEC field.
     */
    public function scopeField($query, string $field)
    {
        return $query->where('field', strtolower($field));
    }

    /**
     * Get the questions associated with this topic.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Scope a query to only include active topics.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Check if the topic has any questions.
     */
    public function hasQuestions(): bool
    {
        return $this->questions()->exists();
    }

    /**
     * Get the count of questions for this topic.
     */
    public function questionCount(): int
    {
        return $this->questions()->count();
    }

    /**
     * Toggle the active status of this topic.
     */
    public function toggleActive(): void
    {
        $this->update(['is_active' => !$this->is_active]);
    }
}
