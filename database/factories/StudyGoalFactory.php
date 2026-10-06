<?php

namespace Database\Factories;

use App\Models\StudyGoal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudyGoalFactory extends Factory
{
    protected $model = StudyGoal::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => 'Sample Goal',
            'type' => 'score_target',
            'target_value' => 80,
            'current_value' => 50,
            'target_date' => now()->addMonth(),
            'is_achieved' => false,
        ];
    }
}
