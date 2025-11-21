<?php

namespace Database\Seeders;

use App\Models\CodeSubmission;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $codeSubmissions = CodeSubmission::all();
        $mentors = User::query()
            ->whereHas('roles', fn ($q) => $q->where('name', 'mentor'))
            ->pluck('id');

        foreach($codeSubmissions as $codeSubmission) {
            Review::factory(rand(1,3))->create([
                'code_submission_id' => $codeSubmission->id,
                'mentor_id' => fn() => $mentors->random(),
            ]);
        }
    }
}
