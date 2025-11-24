<?php

namespace Database\Factories;


use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MentorProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'description' => fake()->paragraph(),
            'experience_years' => rand(1, 10),
            'rate' => fake()->randomFloat(2, 3, 5),
            'is_active' => true,
        ];
    }
}
