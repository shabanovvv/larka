<?php

namespace Database\Seeders;

use App\Models\AiAnalysis;
use App\Models\CodeSubmission;
use Illuminate\Database\Seeder;

class AiAnalysisSeeder extends Seeder
{
    public function run(): void
    {
        $codeSubmissions = CodeSubmission::all();
        foreach ($codeSubmissions as $codeSubmission) {
            AiAnalysis::factory()->create([
                'code_submission_id' => $codeSubmission->id,
            ]);
        }
    }
}
