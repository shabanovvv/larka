<?php

namespace Database\Factories;



use App\Enums\ReviewStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'status' => fake()->randomElement(ReviewStatus::values()),
            'comment' => fake()->realText(),
            'rating' => rand(1, 10),
        ];
    }
}
