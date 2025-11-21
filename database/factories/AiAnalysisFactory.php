<?php

namespace Database\Factories;

use App\Enums\AiProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class AiAnalysisFactory extends Factory
{
    public function definition(): array
    {
        return [
            'provider' => fake()->randomElement(AiProvider::values()),
            'summary' => fake()->realText(),
            'suggestions' => fake()->realText(),
            'score' => fake()->numberBetween(1, 10),
            'raw_response' => [
                'analysis' => fake()->sentence(),
                'confidence' => fake()->randomFloat(2, 0, 1),
                'issues_found' => fake()->numberBetween(0, 5),
                'suggestions_list' => [
                    fake()->sentence(),
                    fake()->sentence(),
                    fake()->sentence(),
                ]
            ],
        ];
    }
}
