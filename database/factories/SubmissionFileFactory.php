<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubmissionFileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'filename' => fake()->word() . '.' . fake()->fileExtension(),
            'content' => fake()->text(500),
        ];
    }
}
