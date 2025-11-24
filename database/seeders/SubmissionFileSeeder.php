<?php

namespace Database\Seeders;

use App\Models\CodeSubmission;
use App\Models\SubmissionFile;
use Illuminate\Database\Seeder;

class SubmissionFileSeeder extends Seeder
{
    public function run(): void
    {
        $codeSubmissions = CodeSubmission::all();
        foreach($codeSubmissions as $codeSubmission) {
            SubmissionFile::factory(rand(1, 3))->create([
                'code_submission_id' => $codeSubmission->id,
            ]);
        }
    }
}
