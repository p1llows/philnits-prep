<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'question_id',
        'code',
        'text',
        'display_order',
    ];

    /**
     * Get the question this choice belongs to.
     */
    public function question(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Check if this choice is correct.
     */
    public function isCorrect(): bool
    {
        return $this->code === $this->question->correct_answer_code;
    }
}
