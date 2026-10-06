<?php

namespace Database\Factories;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

class TopicFactory extends Factory
{
    protected $model = Topic::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'code' => strtoupper(fake()->lexify('TP???')),
            'description' => fake()->sentence(),
            'color' => '#3B82F6',
            'is_active' => true,
        ];
    }
}
