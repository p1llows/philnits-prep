<?php

namespace Database\Factories;

use App\Models\Mistake;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MistakeFactory extends Factory
{
    protected $model = Mistake::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'question_id' => Question::factory(),
            'selected_answer_code' => 'A',
            'correct_answer_code' => 'B',
            'attempt_number' => 1,
            'is_resolved' => false,
        ];
    }
}
