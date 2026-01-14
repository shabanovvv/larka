<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Enums\CodeSubmissionStatus;
use App\Services\AiAnalysisService;
use App\Services\CodeSubmissionService;
use Illuminate\Http\JsonResponse;

class AiAnalysisController extends Controller
{
    public function __construct(
        private readonly AiAnalysisService $aiAnalysisService,
        private readonly CodeSubmissionService $codeSubmissionService
    ){
    }
    /**
     * Возвращает последний AI-анализ по отправке кода.
     * Удобно для polling на фронте: пока ready=false — показываем "анализируется".
     */
    public function showLatest(int $codeSubmissionId): JsonResponse
    {
        $submission = $this->codeSubmissionService->getById($codeSubmissionId);
        $analysis = $this->aiAnalysisService->getLastBySubmissionId($codeSubmissionId);

        return new JsonResponse([
            'status' => $submission->status?->value,
            'ready' => $submission->status === CodeSubmissionStatus::AI_READY,
            'analysis' => $analysis ? [
                'id' => $analysis->id,
                'provider' => $analysis->provider?->value,
                'score' => $analysis->score,
                'summary' => $analysis->summary,
                'suggestions' => $analysis->suggestions,
                'raw_response' => $analysis->raw_response,
                'created_at' => $analysis->created_at,
            ] : null,
        ]);
    }
}



