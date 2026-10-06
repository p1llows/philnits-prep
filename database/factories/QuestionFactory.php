<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition(): array
    {
        return [
            'topic_id' => Topic::factory(),
            'created_by' => User::factory(),
            'question_text' => fake()->paragraph(),
            'explanation' => fake()->paragraph(),
            'source_question_number' => fake()->numberBetween(1, 100),
            'correct_answer_code' => 'A',
            'difficulty' => 'medium',
        ];
    }
}
