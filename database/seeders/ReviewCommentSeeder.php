<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\ReviewComment;
use Illuminate\Database\Seeder;

class ReviewCommentSeeder extends Seeder
{
    public function run(): void
    {
        $reviews = Review::query()->with('codeSubmission.submissionFiles')->get();
        foreach ($reviews as $review) {
            ReviewComment::factory()->create([
                'review_id' => $review->id,
                'file_id' => $review->codeSubmission->submissionFiles->random()->id,
            ]);
        }
    }
}
