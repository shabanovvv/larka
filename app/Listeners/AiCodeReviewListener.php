<?php

namespace App\Listeners;

use App\DTO\AiAnalysisCreateDTO;
use App\Enums\CodeSubmissionStatus;
use App\Events\CodeSubmissionCreated;
use App\Enums\AiProvider;
use App\Services\AiAnalysisService;
use App\Services\CodeSubmissionService;
use Error;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Salavey\AiCodeReviewer\DTO\CodeReviewResultDTO;
use Salavey\AiCodeReviewer\Services\ReviewService;

readonly class AiCodeReviewListener implements ShouldQueue
{
    public function __construct(
        private CodeSubmissionService $codeSubmissionService,
        private ReviewService $reviewService,
        private AiAnalysisService $aiAnalysisService,
    )
    {}

    /**
     * @throws Exception|Error
     */
    public function handle(CodeSubmissionCreated $event): void
    {
        $submission = $event->codeSubmission;
        if (!$submission) {
            return;
        }

        // Идемпотентность: если уже готово/упало — повторно DeepSeek не дергаем.
        if (in_array($submission->status, [CodeSubmissionStatus::AI_READY, CodeSubmissionStatus::AI_FAILED], true)) {
            return;
        }

        // Если по какой-то причине статус не AI_PROCESSING — приводим к нему перед вызовом AI.
        if ($submission->status !== CodeSubmissionStatus::AI_PROCESSING) {
            $this->codeSubmissionService->setStatus($submission, CodeSubmissionStatus::AI_PROCESSING);
        }

        try {
            $code = $submission->code;
            $technologies = $submission->technologies()->pluck('name')->toArray();

            Log::debug('Test debug Listener', [
                'code' => $code,
                'technologies' => $technologies,
            ]);

            $aiResult = $this->reviewService->review($code, implode(',', $technologies));
            $dto = $this->createSuccessDTO($submission->id, $aiResult);
            DB::transaction(function () use ($dto, $submission) {
                $this->aiAnalysisService->store($dto);
                $this->codeSubmissionService->setStatus($submission, CodeSubmissionStatus::AI_READY);
            });
        } catch (Exception|Error $e) {
            // Записываем в AiAnalysis ошибку (чтобы UI мог показать причину), и статус работы.
            $dto = $this->createFailDTO($submission->id, $e->getMessage());
            DB::transaction(function () use ($dto, $submission) {
                $this->aiAnalysisService->store($dto);
                $this->codeSubmissionService->setStatus($submission, CodeSubmissionStatus::AI_FAILED);
            });
        }
    }

    private function createSuccessDTO(int $submissionId, CodeReviewResultDTO $aiResult): AiAnalysisCreateDTO
    {
        return new AiAnalysisCreateDTO(
            $submissionId,
            AiProvider::DEEPSEEK,
            $aiResult->summary,
            $aiResult->suggestions,
            $aiResult->score,
            json_encode([
                'score' => $aiResult->score,
                'summary' => $aiResult->summary,
                'issues' => $aiResult->issues,
                'suggestions' => $aiResult->suggestions,
                'metadata' => $aiResult->metadata,
            ], JSON_UNESCAPED_UNICODE)
        );
    }

    private function createFailDTO(int $submissionId, string $errorMessage): AiAnalysisCreateDTO
    {
        return new AiAnalysisCreateDTO(
            $submissionId,
            AiProvider::DEEPSEEK,
            'Ошибка AI-анализа',
            [],
            0,
            json_encode([
                'error' => $errorMessage,
            ], JSON_UNESCAPED_UNICODE)
        );
    }
}
