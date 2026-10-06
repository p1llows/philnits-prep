<?php

namespace Database\Factories;

use App\Models\Assessment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssessmentFactory extends Factory
{
    protected $model = Assessment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'source_package_name' => 'Sample Assessment',
            'assessment_type' => 'initial',
            'total_questions' => 10,
            'score_percentage' => 70.00,
            'status' => 'in_progress',
            'started_at' => now()->subMinutes(30),
        ];
    }
}
