<?php

namespace App\Repositories;

use App\Models\AiAnalysis;

class AiAnalysisRepository
{
    public function __construct()
    {}

    public function getLastBySubmissionId(int $codeSubmissionId): ?AiAnalysis
    {
        return AiAnalysis::query()
            ->where('code_submission_id', $codeSubmissionId)
            ->orderByDesc('created_at')
            ->first();
    }

    public function save(AiAnalysis $aiAnalysis): AiAnalysis
    {
        $aiAnalysis->save();

        return $aiAnalysis;
    }
}
