<?php

namespace Database\Factories;

use App\Enums\CodeSubmissionStatus;
use App\Models\Technology;
use Illuminate\Database\Eloquent\Factories\Factory;

class CodeSubmissionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'code' => fake()->realText(),
            'status' => fake()->randomElement(CodeSubmissionStatus::values()),
        ];
    }

    public function configure(): CodeSubmissionFactory
    {
        return $this->afterCreating(function ($codeSubmission) {
            $technologies = Technology::query()
                ->inRandomOrder()
                ->limit(rand(1, 3))
                ->pluck('id');

            $codeSubmission->technologies()->attach($technologies);
        });
    }
}
