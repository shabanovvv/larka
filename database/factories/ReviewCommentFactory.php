<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewCommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'line_number' => fake()->numberBetween(1, 10),
            'comment' => fake()->realText(),
        ];
    }
}
