<?php

namespace App\Services;

use App\DTO\AiAnalysisCreateDTO;
use App\Models\AiAnalysis;
use App\Repositories\AiAnalysisRepository;

readonly class AiAnalysisService
{
    public function __construct(private AiAnalysisRepository $aiAnalysisRepository)
    {}

    public function getLastBySubmissionId(int $codeSubmissionId): ?AiAnalysis
    {
        return $this->aiAnalysisRepository->getLastBySubmissionId($codeSubmissionId);
    }

    public function store(AiAnalysisCreateDTO $dto): AiAnalysis
    {
        $aiAnalysis = new AiAnalysis();
        $aiAnalysis->provider = $dto->provider;
        $aiAnalysis->summary = $dto->summary;
        $aiAnalysis->suggestions = $dto->suggestions;
        $aiAnalysis->score = $dto->score;
        $aiAnalysis->raw_response = $dto->rawResponse;
        $aiAnalysis->codeSubmission()->associate($dto->codeSubmissionId);

        return $this->aiAnalysisRepository->save($aiAnalysis);
    }
}
